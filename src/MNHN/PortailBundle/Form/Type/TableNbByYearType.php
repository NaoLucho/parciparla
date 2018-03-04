<?php 

namespace MNHN\PortailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Doctrine\ORM\EntityRepository;

class TableNbByYearType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        
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
        return 'table_nb_by_year';
    }
}