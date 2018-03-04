<?php

namespace Application\Sonata\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use MNHN\AdminBundle\Entity\G_List;
use MNHN\AdminBundle\Entity\G_ListItem;

class UserType extends AbstractType {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->remove('username')
        ->add('lastname', null, array(
            'label' => 'Nom'
        ))
        ->add('firstname',null, array(
            'label' => 'Prénom'
        ))
        ->add('email')
        ->add('imageFile', FileType::class, array(
            'required' => false,
            'label' => 'Avatar'
        ))
        
        
        ->add('capacity', null, array(
            'label' => 'Fonction',
            'required' => false
        ))
        ->add('skills', 'entity', [
            'class' => 'MNHNPortailBundle:Skill',
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'label' => 'Compétences'
        ])
        ->add('theme', EntityType::class, array(
            'required' => false,
            'class' => 'MNHNAdminBundle:G_ListItem',
            'label' => 'Mes thématiques étudiées',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('listitem')
                    ->leftJoin('listitem.list', 'list')
                    ->where('list.name = :lname')
                    ->orderBy('listitem.name', 'ASC')
                    ->setParameter('lname', 'themes');
            },
            'choice_label' => 'name',
            'attr' => array(
                'class' => 'form-control theme-field',
            ),
            'multiple' => true
        ))
        ->add('taxon', 'entity', array(
            'required' => false,
            'class' => 'MNHNAdminBundle:G_ListItem',
            'label' => 'Mes taxons étudiées',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('listitem')
                    ->leftJoin('listitem.list', 'list')
                    ->where('list.name = :lname')
                    ->orderBy('listitem.name', 'ASC')
                    ->setParameter('lname', 'taxons');
            },
            'choice_label' => 'name',
            'attr' => array(
                'class' => 'form-control taxon-field',
            ),
            'multiple' => true,
        ));

        $builder->addEventListener (FormEvents::PRE_SUBMIT, function(FormEvent $event)
        {
            $data = $event->getData();
            $form = $event->getForm();
    
            if (isset($data['taxon'])) {
                    //Ajout de nouveaux taxons si non existants
                $taxons = $data['taxon'];
    
                $data['taxon'] = [];
        
                    
                    //On récupère la liste taxon
                $taxonList = $this->em->getRepository(G_List::class)->findOneByName('taxons');
        
                    //On créé un taxon si non existant
                foreach ($taxons as $taxon) {
                        // On vérifie s'il existe
                    $taxonItem = $this->em->getRepository(G_ListItem::class)->find($taxon);
                    if ($taxonItem == null) {
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
    
            if (isset($data['theme'])) {
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
    
            $event->setData($data);
        }
        ); 
    }


    public function getParent() {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix() {
        return 'app_user_profile';
    }
}