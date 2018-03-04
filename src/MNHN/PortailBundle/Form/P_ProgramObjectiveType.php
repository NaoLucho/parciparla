<?php

namespace MNHN\PortailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

class P_ProgramObjectiveType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objective', EntityType::class, array(
                'required' => true,
                //'disabled' => true,
                'class' => 'MNHNAdminBundle:G_ListItem',
                // 'placeholder' => "...",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('listitem')
                        ->leftJoin('listitem.list', 'list')
                        ->where('list.name = :lname')
                        ->orderBy('listitem.name', 'ASC')
                        ->setParameter('lname', 'program_objectives');
                },
                'choice_label' => 'name',
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Objectif',
                //'hidden' => true
            ))
            // ->add('objective', HiddenType::class, array(
            //     'property_path' => 'id',
                
            // ))

            ->add('priority', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                ),
                'label' => 'Priorité',
                // 'attr' => array(
                //     'min' => 1,
                //     'max' => 3
                // )
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MNHN\PortailBundle\Entity\P_Program_Objective'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mnhn_portailbundle_program_objective';
    }


}
