<?php

namespace MNHN\PortailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

class P_ProgramNbByYearType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('program', EntityType::class, array()
            // ->add('objective', EntityType::class, array(
            //     'required' => true,
            //     'disabled' => true,
            //     'class' => 'BuilderBundle:G_ListItem',
            //     // 'placeholder' => "...",
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('listitem')
            //             ->leftJoin('listitem.list', 'list')
            //             ->where('list.name = :lname')
            //             ->orderBy('listitem.name', 'ASC')
            //             ->setParameter('lname', 'program_objectives');
            //     },
            //     'choice_label' => 'name',
            //     'attr' => array(
            //         'class'     => 'form-control',
            //     ),
            //     'label' => 'Objectif',
            // ))
            ->add('year', null, array(
                //'label' => 'Année'
            ))
            // nb inutile puisque le champs est créé en HTML dans fields_program_template.html.twig
            ->add('nb', null, array(
                'attr' => array(
                    'min' => '0'
                ),
                // 'empty_data' => '0',
                // 'required' => true
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MNHN\PortailBundle\Entity\P_Program_ValueByYear'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mnhn_portailbundle_program_nb_by_year';
    }


}
