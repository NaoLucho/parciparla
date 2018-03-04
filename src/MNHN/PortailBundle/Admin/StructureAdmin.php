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

class StructureAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        //To add fields config of existing FormType
        // $form = new P_StructureFormType();
        // $form->buildForm($formMapper->getFormBuilder(),array());

        $builder = $formMapper->getFormBuilder()->getFormFactory()->createBuilder(P_StructureFormType::class);

        $children = $builder->all();

        foreach ($children as $child => $child) {
            $formMapper->with('Structure')
                ->add($builder->get($child))
            ->end();
        }
        $formMapper->add('owner', EntityType::class, array(
                'required' => true,
                'class' => 'ApplicationSonataUserBundle:User',
                'placeholder' => '...',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'username',
                'attr' => array(
                    'class'     => 'form-control',
                ),
            ));

        // $formMapper
        //     ->add('structure_form', new P_StructureFormType(), array(), array('type' => 'form'))
        //     ->add('name', TextType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Nom de la Structure',
        //     ))
        //     ->add('imageFile', FileType::class, array(
        //         'label' => 'Logo',
        //         'required' => false
        //     ))
        //     ->add('structureType', EntityType::class, array(
        //         'required' => true,
        //         'class' => 'MNHNAdminBundle:G_ListItem',
        //         'placeholder' => "...",
        //         'query_builder' => function (EntityRepository $er) {
        //             return $er->createQueryBuilder('listitem')
        //                 ->leftJoin('listitem.list', 'list')
        //                 ->where('list.name = :lname')
        //                 ->orderBy('listitem.name', 'ASC')
        //                 ->setParameter('lname', 'structureType');
        //         },
        //         'choice_label' => 'name',
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Type de Structure',
        //     ))
        //     ->add('owner', EntityType::class, array(
        //         'required' => true,
        //         'class' => 'ApplicationSonataUserBundle:User',
        //         'placeholder' => '...',
        //         'query_builder' => function (EntityRepository $er) {
        //             return $er->createQueryBuilder('u')
        //                 ->orderBy('u.username', 'ASC');
        //         },
        //         'choice_label' => 'username',
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //     ))
        //     ->add('description', TextareaType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control tinymce',
        //             'rows'      => 10,
        //         ),
        //         'label' => 'Description de la Structure',
        //     ))
        //     ->add('address', TextType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Adresse',
        //     ))
        //     ->add('postalCode', TextType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Code postal',
        //     ))
        //     ->add('city', TextType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Ville',
        //     ))
        //     ->add('phone', TextType::class, array(
        //         'required' => false,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Téléphone',
        //     ))
        //     ->add('email', TextType::class, array(
        //         'required' => true,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Email',
        //     ))
        //     ->add('webSite', TextType::class, array(
        //         'required' => false,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Site web',
        //     ))
        //     ->add('facebook', TextType::class, array(
        //         'required' => false,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Lien Facebook',
        //     ))
        //     ->add('twitter', TextType::class, array(
        //         'required' => false,
        //         'attr' => array(
        //             'class'     => 'form-control',
        //         ),
        //         'label' => 'Lien Twitter',
        //     ));
        //     // ->add('logo', MediaSingleType::class, array(
        //     //     'required' => false,
        //     //     'attr' => array(
        //     //         'class'     => 'form-control',
        //     //         'data-media-type' => 'image',
        //     //     ),
        //     //     'label' => 'Logo',
        //     // ));

        //     // if($options['admin']) {
                $formMapper->add('isActive', ChoiceType::class, array(
                    'required' => true,
                    'choices' => array(
                        'Activer' => true,
                        'En attente' => false,
                    ),
                    'expanded' => true,
                    'label' => 'Validation admin de la publication de la Structure',
                ));
            // }


            // $formMapper->add('isPorteur', ChoiceType::class, array(
            //     'required' => true,
            //     'choices' => array(
            //         'Yes' => true,
            //         'No' => false,
            //     ),
            //     'expanded' => true,
            //     'disabled' => true,
            // ))
            // ->add('isRelai', ChoiceType::class, array(
            //     'required' => true,
            //     'choices' => array(
            //         'Yes' => true,
            //         'No' => false,
            //     ),
            //     'expanded' => true,
            //     'disabled' => true,
            // ))
            // ->add('isCollectifCoordo', ChoiceType::class, array(
            //     'required' => true,
            //     'choices' => array(
            //         'Yes' => true,
            //         'No' => false,
            //     ),
            //     'expanded' => true,
            // ));
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
                'label' => "Structure"
            ))
            ->add('structureType.name', null, array(
                'label' => "Type"
            ))
            ->add('owner.username', null, array(
                'label' => "Gestionnaire"
            ))
            ->add('isActive', null, array(
                'label' => "Activé?",
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