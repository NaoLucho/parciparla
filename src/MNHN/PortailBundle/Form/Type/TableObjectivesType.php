<?php 

namespace MNHN\PortailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Doctrine\ORM\EntityRepository;

class TableObjectivesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        // $listname = 'program_objectives';
        // $resolver->setDefaults(array(
        //     'class' => 'MNHNAdminBundle:G_ListItem',
        //     'query_builder' => (function (EntityRepository $er) use ($listname) {
        //         return $er->createQueryBuilder('listitem')
        //             ->leftJoin('listitem.list', 'list')
        //             ->where('list.name = :lname')
        //             ->orderBy('listitem.name', 'ASC')
        //             ->setParameter('lname', $listname);
        //     }),
        //     // 'choices' => array(
        //     //     'Standard Shipping' => 'standard',
        //     //     'Expedited Shipping' => 'expedited',
        //     //     'Priority Shipping' => 'priority',
        //     // ),
        //     //'choices_as_values' => true,
        // ));
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_objectives';
    }
}