<?php
namespace MNHN\PortailBundle\Controller\Program;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\RedirectResponse;

use MNHN\PortailBundle\Entity\P_Program;
use MNHN\PortailBundle\Entity\P_Program_Objective;
use MNHN\PortailBundle\Entity\P_Program_ValueByYear;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

//FOR FORMBUILDER:
use MNHN\AdminBundle\Utils\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use MNHN\PortailBundle\Form\P_ProgramFormType;

use Symfony\Component\Form\FormError;


// UTILE?
// use Symfony\Component\Debug\Debug;
// Debug::enable();
// use Symfony\Component\Debug\ErrorHandler;
// ErrorHandler::register();

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProgramController extends Controller
{

    public function showAction($slug, 
    $id = 0,
    $builderparams,
    Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // $id = $request->request->get('id');

        $program = null;
        if($id>0)
        {
            $program = $em->getRepository('MNHNPortailBundle:P_Program')->find($id);
        }

        $template_repo = $this->container->getParameter('template_repo');
        if ($this->get('twig')->getLoader()->exists(':' . $template_repo . '/views:'.$slug.'html.twig')) {
            
            $pageparams = array(
                'slug' => $slug,
                'program' => $program,
                'id' => $id,
                'request' => $request,
            );
            $pageparams = array_merge($pageparams, $builderparams);
            return $this->render(':' . $template_repo . '/views:'.$slug.'.html.twig', $pageparams);
        }

        return $this->render(
            "MNHNPortailBundle:Program:show.html.twig",
            array(
                'id' => $id,
                'program' => $program
            )
        );
    }

    /**
     * @Security("has_role('ROLE_PRO')")
     */
    public function createByStepAction(Request $request, $step = 1, $id = 0)
    {
        //GESTION DES DROITS D'ACCESS:
        // Choisir entre Annotation @Security ou en utilisant la fonction:
        //$this->denyAccessUnlessGranted('ROLE_PRO', null, 'Vous n\'avez pas les droits d\'accès à cette page.');

        //var_dump($request->request->all());
        //var_dump($postedparams);
        //$pageSlug = $request->request->get('slug');
        $errormessage = "";
        $structureCree = false;
        $programExist = false;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = null;
        $errors = null;

        if ($id > 0) {
            $entity = $em
                ->getRepository('MNHNPortailBundle:P_Program')
                ->find($id);
        }
        if ($entity == null) {
            $entity = new P_Program();
        } else {
            $programExist = true;
        }

        if (!$programExist) {
            //INITIALISATION DES DONNEES
            $qb = $em->getRepository('MNHNAdminBundle:G_ListItem')->createQueryBuilder('listitem')
                ->leftJoin('listitem.list', 'list')
                ->where('list.name = :lname')
                ->orderBy('listitem.id', 'ASC')
                ->setParameter('lname', 'program_objectives');
            // Execute Query
            $objectiveItems = $qb->getQuery()->getResult();
            foreach ($objectiveItems as $objectif) {
                $progObjective = new P_Program_Objective();
                $progObjective->setProgram($entity);
                $progObjective->setObjective($objectif);
                $entity->addObjective($progObjective);
            }
        }
        if ($step == 4 && $entity != null) {
            for ($i = 0; $i < 10; $i++) {
                $dateToAdd = date("Y") - 9 + $i;
                $alreadyExistSubscriber = false;
                $alreadyExistActiveUser = false;
                $alreadyExistCollectedData = false;
                foreach ($entity->getNbSubscriberByYear() as $nbByYear) {
                    if ($nbByYear->getYear() == $dateToAdd) {
                        $alreadyExistSubscriber = true;
                    }
                }
                foreach ($entity->getNbActiveUserByYear() as $nbByYear) {
                    if ($nbByYear->getYear() == $dateToAdd) {
                        $alreadyExistActiveUser = true;
                    }
                }
                foreach ($entity->getNbCollectedDataByYear() as $nbByYear) {
                    if ($nbByYear->getYear() == $dateToAdd) {
                        $alreadyExistCollectedData = true;
                    }
                }

                if (!$alreadyExistSubscriber) {
                    $nbsubscriberInit = new P_Program_ValueByYear();
                    $nbsubscriberInit->setYear($dateToAdd);
                    $nbsubscriberInit->setProgramNbSubscriber($entity);
                    $entity->addNbSubscriberByYear($nbsubscriberInit);
                }
                if (!$alreadyExistActiveUser) {
                    $nbsubscriberInit = new P_Program_ValueByYear();
                    $nbsubscriberInit->setYear($dateToAdd);
                    $nbsubscriberInit->setProgramNbActiveUser($entity);
                    $entity->addNbActiveUserByYear($nbsubscriberInit);
                }
                if (!$alreadyExistCollectedData) {
                    $nbsubscriberInit = new P_Program_ValueByYear();
                    $nbsubscriberInit->setYear($dateToAdd);
                    $nbsubscriberInit->setProgramNbCollectedData($entity);
                    $entity->addNbCollectedDataByYear($nbsubscriberInit);
                }

            }
        }
            
       
        // $objectiveItem = $em->getRepository('MNHNAdminBundle:G_ListItem')->findOneBy(array('name' => 'objective1'));
        // $progObjective1 = new P_Program_Objective();
        // $progObjective1->setProgram($entity);
        // $progObjective1->setObjective($objectiveItem);
        // $entity->addObjective($progObjective1);
        // chercher liste objectif
        
        // add entity objectif => each objectif

        

        //Load F_FORM => SERVICE getform($formName, $MODE[VIEW/CREATE/EDIT], $current_user)
        $f_form = $em
            ->getRepository('MNHNAdminBundle:F_Form')
            ->findOneBy(array('name' => 'program_step' . $step));

        //MODE SANS P_ProgramFormType:
        $formBuilder = $this->get('form.factory')->createBuilder(
            FormType::class,
            $entity,
            array(
                'validation_groups' => array('default', 'program_step' . $step, 'dynamic')
            )
        );
        
        // $formBuilder = $this->createFormBuilder($entity, array(
        //     'validation_groups' => array('program_step'.$step)
        // ));
        $formBuilder = FormBuilder::createForm($f_form, $formBuilder, $user, $em, $step);

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // // MODE AVEC P_ProgramFormType:
        // $form = $this->get('form.factory')->create(P_ProgramFormType::class, $entity, [
        //     "f_form" => $f_form,
        //     "user" =>  $user,
        //     "em" =>  $em,
        //     "entity" =>  $entity
        // ]);

        if ($step < 4) {
            $form->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'Je passe à l\'étape suivante',
                    'attr' => array('class' => 'step-to btn btn-danger btn-lg pull-right')
                )
            );
        } else {
            $form->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'Je soumets mon observatoire',
                    'attr' => array('class' => 'step-to btn btn-danger btn-lg pull-right')
                )
            );
        }

        //$validator = Validation::createValidator();
        //$errors = $validator->validate($entity, null, array('Observatoire_Step'.$step));
        //dump($errors);
        if ($request->isMethod('POST')) {
            //SAVE SPECIFIC FIELDS VALUES:
            //Localisations:
            $typegeo = $request->request->get('portee_geo');
            $hasLocalisations = $request->request->get('localisations');
            $localisations = [];

            if ($hasLocalisations && $typegeo) {
                $codes = $request->request->get($typegeo);
                if (isset($codes)) {
                    if (is_array($codes)) {
                        //get geoms by $codes
                        $qb = $em->getRepository('MNHNPortailBundle:Geom')->createQueryBuilder('geo')
                            ->where('geo.id in (:ids)')
                            ->setParameter('ids', $codes);
                        // Execute Query
                        $geoms = $qb->getQuery()->getResult();
                        //$geoms = [12,23,22];
                        $localisations = $geoms;
                    
                        //Others not in db geom for the moment
                    } else {
                        //Code postal
                        $qb = $em->getRepository('MNHNPortailBundle:Geom')->createQueryBuilder('geo')
                            ->where('geo.code = :code')
                            ->setParameter('code', $codes);
                        $geom = $qb->getQuery()->getResult();
                        $localisations = $geom;
                    }
                }

                $entity->setLocalisations($localisations);
                //dump($localisations);
            }
            $form->handleRequest($request);
            //var_dump($form->getErrorsAsString());
            //dump($form);
            if ($form->isSubmitted()) {
                if ($hasLocalisations) {
                    if (count($localisations) == 0) {
                        $form->get('f_localisations')->addError(new FormError('Indiquez la portée géographique.'));
                    }
                }
                //$this->addFlash("warning", "Formulaire submitted");

                if ($form->isValid()) {

                    //IF NEW PROGRAM: define owner and persist program
                    if (!$programExist) {
                        //Add default values:
                        $entity->addOwnersAnim($user);
    
                        //Declare new object to save
                        $em->persist($entity);
                    }



                    if ($step == 4) {
                        //Formulaire de création terminé:
                        $entity->setIsFinalizedForm(true);
                        
                        // Adding a success type message
                        $this->addFlash("success", "Votre observatoire est enregistré.");
                    }
                    
                    //Sauvegarde:
                    $em->flush();

                    if ($step < 4) {
                        //REDIRECT TO NEW STEP
                        $step = $step + 1;
                        // $url = $this->generateUrl('fos_user_registration_confirmed');
                        // $response = new RedirectResponse($url);

                        //DEV INFO: A COMMENTER POUR AFFICHER LES ELEMENTS POSTES (REQUEST)
                        return $this->redirectToRoute('create_program_next_step', array('step' => $step, 'id' => $entity->getId()));
                    }



                } else {
                    $errors = $form->getErrors(true, false);
                    //dump($errors);
                    $errormessage = "Formulaire invalide, veuillez corriger les champs indiqués par <span class='glyphicon glyphicon-exclamation-sign'>";
                    //$this->addFlash("errorEEE", $errors);
                }
            }

        }
        
        //flashbag not working correctly
        return $this->render(
            "MNHNPortailBundle:Program:step.html.twig",
            array(
                'form' => $form->createView(),
                'f_form' => $f_form,
                'errormessage' => $errormessage,
                'stepactive' => $step,
                'request' => $request,
                'errors' => $errors,
                //'postedparams' => $postedparams
            )
        );
    }

}