<?php

namespace MNHN\PortailBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use MNHN\PortailBundle\Form\P_StructureFormType;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use MNHN\AdminBundle\Utils\Form\FormBuilder;

class ProgramAdmin extends AbstractAdmin
{

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                //var_dump( parent::getTemplate($name));
                return 'MNHNAdminBundle::Field\admin_fields_program_template.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        //$programId = $this->getSubject()->getId();

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');

        // $em = $this->modelManager->getEntityManager(YourBundle:Item);
        // $item = $em->getRepository('YourBundle:Item')->find($id);
        // $item->getName();

        $f_form = $em
        ->getRepository('MNHNAdminBundle:F_Form')
        ->findOneBy(array('name' => 'admin_program'));
        
        //$user =  $this->get('security.context')->getToken()->getUser();
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        //$formMapper->with($f_form->getTitle());
        $formMapper = FormBuilder::createForm($f_form, $formMapper, $user, $em);
        //$formMapper->end();

        $formMapper->add('isFinalizedForm', ChoiceType::class, array(
            'required' => true,
            'choices' => array(
                'Oui, les étapes sont terminées' => true,
                'Non, la création n\'est pas terminée' => false,
            ),
            'expanded' => true,
            'label' => 'Le formulaire de création est-il complet?',
        ));
        $formMapper->add('isActiveProgram', ChoiceType::class, array(
            'required' => true,
            'choices' => array(
                'Activer et rendre public' => true,
                'En attente' => false,
            ),
            'expanded' => true,
            'label' => 'Validation admin de la publication de l\'observatoire',
        ));
            
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('name')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array(
                'label' => "Program"
            ))
            ->add('status.name')
            ->add('structureAnim.name')
            ->add('isFinalizedForm', null, array(
                'label' => "Formulaire complet?",
                'editable' => true
            ))
            ->add('isActiveProgram', null, array(
                'label' => "Observatoire publié?",
                'editable' => true
            ))
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('name')
       ;
    }
}