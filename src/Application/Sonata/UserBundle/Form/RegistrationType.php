<?php

namespace Application\Sonata\UserBundle\Form;


use Application\Sonata\UserBundle\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MNHN\PortailBundle\Form\P_StructureFormType;
use MNHN\AdminBundle\Entity\G_List;
use MNHN\AdminBundle\Entity\G_ListItem;
use MNHN\PortailBundle\Entity\P_Structure;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;


class RegistrationType extends AbstractType
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('username')
        // ->add('imageFile', FileType::class, array(
        //     'label' => 'Chargez votre avatar',
        //     'required' => false
        // ))
        ->add('firstname', null, array(
            'label' => 'Prénom *',
            'required' => true
        ))
        ->add('lastname', null, array(
            'label' => 'Nom *',
            'required' => true
        ))
        // ->add('capacity', null, array(
        //     'label' => 'Fonction',
        //     'required' => false
        // ))
        // ->add('skills', 'entity', [
        //     'class' => 'MNHNPortailBundle:Skill',
        //     'choice_label' => 'name',
        //     'multiple' => true,
        //     'expanded' => true,
        //     'label' => 'Compétences'
        // ])
        // ->add('theme', EntityType::class, array(
        //     'required' => false,
        //     'class' => 'MNHNAdminBundle:G_ListItem',
        //     'label' => 'Mes thématiques étudiées',
        //     'query_builder' => function (EntityRepository $er) {
        //         return $er->createQueryBuilder('listitem')
        //             ->leftJoin('listitem.list', 'list')
        //             ->where('list.name = :lname')
        //             ->orderBy('listitem.name', 'ASC')
        //             ->setParameter('lname', 'themes');
        //     },
        //     'choice_label' => 'name',
        //     'attr' => array(
        //         'class' => 'form-control theme-field',
        //     ),
        //     'multiple' => true
        // ))
        // ->add('taxon', 'entity', array(
        //     'required' => false,
        //     'class' => 'MNHNAdminBundle:G_ListItem',
        //     'label' => 'Mes taxons étudiées',
        //     'query_builder' => function (EntityRepository $er) {
        //         return $er->createQueryBuilder('listitem')
        //             ->leftJoin('listitem.list', 'list')
        //             ->where('list.name = :lname')
        //             ->orderBy('listitem.name', 'ASC')
        //             ->setParameter('lname', 'taxons');
        //     },
        //     'choice_label' => 'name',
        //     'attr' => array(
        //         'class' => 'form-control taxon-field',
        //     ),
        //     'multiple' => true,
        // ))
        ->add('structures', 'entity', array(
            'class' => 'MNHNPortailBundle:P_Structure',
            'placeholder' => "CHOISISSEZ VOTRE STRUCTURE",
            'choice_label' => 'name',
            'label' => false,
            'multiple' => false,
            'attr' => array(
                'class' => 'structure_list form-control'
            )
        ))
        ->add('ownedStructures', CollectionType::class, array(
            'entry_type' => 'MNHN\PortailBundle\Form\P_StructureFormType',
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
            'required' => true,
            'label' => false
        ))
        ->add('cgu', CheckboxType::class, array(
            'mapped' => false,
            'constraints' => array(
                new IsTrue(array(
                    'message' => 'Vous devez accepter les CGU avant de pouvoir passer à l\'étape suivante'
                )
                )),
                'label' => false
        ))
        ->add('emailCheck', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new IsTrue(array(
                        'message' => 'Vous devez accepter de rendre public votre adresse Email à tous les professionnels avant de pouvoir passer à l\'étape suivante'
                    ))
                ),
                'label' => false,
            ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            // if (isset($data['ownedStructures']) && count($data['ownedStructures']) > 0) {
            //     $structure = $data['ownedStructures'][0];
                
            //     if($structure['latitude'] == "" || $structure['longitude'] == ""  ) {
            //         echo "<script>alert('Vous devez renseigner une localisation géographique sur la cartographie de la structure nouvellement créée. 
            //         Vous pouvez la renseigner via la recherche d\'adresse ou en cliquant directement dessus')</script>";
            //         return;
            //     }

            // }

            if(isset($data['taxon'])) {
                //Ajout de nouveaux taxons si non existants
                $taxons = $data['taxon'];
                
                $data['taxon'] = [];
    
                
                //On récupère la liste taxon
                $taxonList = $this->em->getRepository(G_List::class)->findOneByName('taxons');
    
                //On créé un taxon si non existant
                foreach($taxons as $taxon) {
                    // On vérifie s'il existe
                    $taxonItem = $this->em->getRepository(G_ListItem::class)->find($taxon);
                    if ($taxonItem == null)
                    {
                       // Create the new taxon
                        $taxonItem = new G_ListItem();
                        $taxonItem->setName($taxon);
                        $taxonItem->setList($taxonList);
                        $this->em->persist($taxonItem);
                        $this->em->flush();
                    }
                    array_push($data['taxon'], $taxonItem->getId() . "");
    
                }
            }

            if(isset($data['theme'])) {
                //Ajout d'une nouvelle thématique si non existante
                $themes = $data['theme'];
    
                $data['theme'] = [];
    
                
                //On récupère la liste des thématiques
                $themeList = $this->em->getRepository(G_List::class)->findOneByName('themes');
    
                //On créé une thématique si non existante
                foreach ($themes as $theme) {
                    // On vérifie s'il existe
                    $themeItem = $this->em->getRepository(G_ListItem::class)->find($theme);
                    if ($themeItem == null) {
                       // Create the new theme
                        $themeItem = new G_ListItem();
                        $themeItem->setName($theme);
                        $themeItem->setList($themeList);
                        $this->em->persist($themeItem);
                        $this->em->flush();
                    }
                    array_push($data['theme'], $themeItem->getId() . "");
    
                }
            }

            //Si la personne ne déclare pas une nouvelle structure
            if (isset($data['ownedStructures']) && count($data['ownedStructures']) > 0) {
                $form->remove('structures');
                $form->add('structures', 'entity', array(
                    'required' => false,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'label' => false,
                    'multiple' => false,
                    'class' => 'MNHNPortailBundle:P_Structure',
                    'attr' => array(
                        'class' => 'structure_list'
                    )
                ));
            } else {
                $form->remove('ownedStructures');
                $form->add('ownedStructures', CollectionType::class, array(
                    'entry_type' => 'MNHN\PortailBundle\Form\P_StructureFormType',
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    'required' => false,
                    'label' => false
                ));
            }

            $event->setData($data);
        }); 
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
    
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
        
    }

}