<?php

namespace MNHN\PortailBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


use MNHN\PortailBundle\Entity\P_Structure;
/**
 *
 * Class P_StructureType
 */
class P_StructureFormType extends AbstractType
{
     
    /**
     * @author Aaron Hartnell et Louis Watrin
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Nom de la Structure *',
            ))
            ->add('imageFile', FileType::class, array(
                'label' => 'Logo *',
                'required' => true
            ))
            ->add('structureType', EntityType::class, array(
                'required' => true,
                'class' => 'MNHNAdminBundle:G_ListItem',
                'placeholder' => "...",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('listitem')
                        ->leftJoin('listitem.list', 'list')
                        ->where('list.name = :lname')
                        ->orderBy('listitem.name', 'ASC')
                        ->setParameter('lname', 'structureType');
                },
                'choice_label' => 'name',
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Type de Structure *',
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control tinymce',
                    'rows'      => 10,
                ),
                'label' => 'Description de la Structure *',
            ))
            ->add('address', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Adresse *',
            ))
            ->add('postalCode', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Code postal *',
            ))
            ->add('city', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Ville *',
            ))
            ->add('latitude', NumberType::class, array(
                'required' => true,
                'attr' => [
                //     'disabled' => 'disabled'
                    'readonly' => true,
                    'class' => 'label-like'
                ]
            ))
            ->add('longitude', NumberType::class, array(
                'required' => true,
                'attr' => [
                //     'disabled' => 'disabled'
                    'readonly' => true,
                    'class' => 'label-like'
                ]
            ))
            ->add('phone', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Téléphone',
            ))
            ->add('email', EmailType::class, array(
                'required' => true,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Email *',
            ))
            ->add('website', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Site web',
            ))
            ->add('facebook', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Lien Facebook',
            ))
            ->add('twitter', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class'     => 'form-control',
                ),
                'label' => 'Lien Twitter',
            ));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => P_Structure::class
        ));
     }

    // /**
    //  * @return string
    //  */
    // public function getName()
    // {
    //     return 'portail_structure_form'; //csf->portail
    // }
}