<?php

namespace Builder\ListBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class G_ListAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        
        if ($securityContext->isGranted('ROLE_SUPER_ADMIN'))
        {
            $formMapper
                ->add('name', 'text', array(
                    'label' => 'Key'
                ));
        }
        $formMapper
            ->add('title', 'text', array(
                'label' => 'Liste'
            ))
            ->add('listItems', 'sonata_type_collection', array(
                'by_reference' => false,
                'type_options' => array(
                    'delete' => true
                )
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'order',
            ))
        ;
        if ($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->add('locked', null, array(
                    'label' => 'Locked by super admin'
                ));
        }

    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('name')
            ->add('title')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        
        if( $securityContext->isGranted('ROLE_SUPER_ADMIN') ) {
            $listMapper
            ->addIdentifier('name', null, array(
                'label' => "Key"
            ));
        }
        $listMapper
        ->addIdentifier('title', null, array(
            'label' => "List"
        ))
        ->add('listItems', null, array(
            'associated_property' => 'name')
        )
       ;
    }

    public function createQuery($context = 'list')
    {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $query = parent::createQuery($context);
        if (!$securityContext->isGranted('ROLE_SUPER_ADMIN')) {

            $query->andWhere(
                $query->expr()->eq($query->getRootAliases()[0] . '.locked', ':locked')
            );
            $query->setParameter('locked', false);
        }
        return $query;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('name')
       ;
    }
}