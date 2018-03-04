<?php
namespace MNHN\PortailBundle\Controller\Structure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use MNHN\AdminBundle\Controller\Builder\Utils;

use Symfony\Component\HttpFoundation\RedirectResponse;

use MNHN\PortailBundle\Entity\P_Structure;

use MNHN\PortailBundle\Form\P_StructureFormType;

class StructureController extends Controller
{
    public function listAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $structures = $em->getRepository('MNHNPortailBundle:P_Structure')->findBy(array('isActive' => true));

        $structureArray =  [];

        $structure_logo_route = $this->container->getParameter('structure_logo_route');        

        foreach($structures as $key => $structure) {
            $structureArray[$key]["name"] = $structure->getName();
            $structureArray[$key]["lat"] = $structure->getLatitude();
            $structureArray[$key]["long"] = $structure->getLongitude();
            $structureArray[$key]["id"] = $structure->getId();
            $structureArray[$key]["logo"] = $structure_logo_route . '/' . $structure->getImageName();
            
        }

        $this->get('mnhn_portail.js_vars')->structures = $structureArray;

        return $this->render('MNHNPortailBundle:Structure:list.html.twig', array(
            'structures' => $structures
        ));
    }

    //Request doit etre envoyé dans l'appel du render controller pour correspondre à la request parente
    public function proCreateStructureAction(Request $request)
    {

        $structureCree = false;

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $structure = new P_Structure();

        $form = $this->get('form.factory')->create(P_StructureFormType::class, $structure);

        $form->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer',
            'attr'  => array('class' => 'btn btn-default pull-right')
        ));

        if($request->isMethod('POST')) {

            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
                $this->addFlash("warning", "Formulaire submitted");
                if ($form->isValid()) {

                    $structure->setOwner($user);
                    $em->persist($structure);
                    $em->flush();
    
                    // Adding a success type message
                    $this->addFlash("success", "Structure créée");
    
                    $this->get("session")->getFlashBag()->add("success", "structure enregistrée");

                    $structureCree = true;


                } else {
                    $errors = $form->getErrors();
    
                    $this->addFlash("error", $errors);
                }
            }
    
        }

        //flashbag not working correctly
        return $this->render(
            "MNHNPortailBundle:Structure:Front/procreate.html.twig", array(
                'form' => $form->createView(),
                'structureCree' => $structureCree
        ));
    }

    public function showAction(
        $slug = "fiche_structure", 
        $id = 0,
        $builderparams,
        Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //$id = $request->query->get('id');
        $structure = null;
        if($id>0)
        {
            $structure = $em->getRepository('MNHNPortailBundle:P_Structure')->find($id);
        }
        
        $template_repo = $this->container->getParameter('template_repo');
        if ($this->get('twig')->getLoader()->exists(':' . $template_repo . '/views:'.$slug.'.html.twig')) {
            
            $pageparams = array(
                'slug' => $slug,
                'structure' => $structure,
                'id' => $id,
                'request' => $request,
            );
            $pageparams = array_merge($pageparams, $builderparams);
            return $this->render(':' . $template_repo . '/views:'.$slug.'.html.twig', $pageparams);
        }
        return $this->render(
            "MNHNPortailBundle:Structure:Front/show.html.twig", array(
                'id' => $id,
                'structure' => $structure
        ));
    }

}