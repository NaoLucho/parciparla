<?php

namespace MNHN\PortailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BuilderBundle\Utils\Form\FormBuilder;

// PERMET DE CREER LE FORMULAIRE Program avec le builder
class P_ProgramFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = FormBuilder::createForm(
            $options["f_form"],
            $builder,//$options["formBuilder"],
            $options["user"],
            $options["em"],
            $options["entity"]);
        //$builder->add('name');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MNHN\PortailBundle\Entity\P_Program',
            'f_form' => null,
            'user' => null,
            'em' => null,
            'entity' => null
        ));
    }
}
