<?php

namespace App\Admin\Sonata;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

class SonataUserUserAdmin extends BaseUserAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        parent::configureDatagridFilters($datagrid);

        $datagrid->add('createdAt', DateRangeFilter::class, ['field_type'=> DateRangePickerType::class, 'label' => 'Creation Date'])
            ->add('updatedAt', DateRangeFilter::class, ['field_type'=> DateRangePickerType::class, 'label' => 'Modification Date']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        parent::configureShowFields($show);

        $show->add('createdAt', null, ['label' => 'Creation Date'])
            ->add('updatedAt', null, ['label' => 'Modification Date']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        parent::configureListFields($list);

        $list->remove('createdAt')
            ->add('createdAt', null, ['label' => 'Creation Date'])
            ->add('updatedAt', null, ['label' => 'Modification Date']);
    }

    protected function configureExportFields(): array
    {
        return ['id', 'userName', 'email', 'roles', 'enabled' ,'createdAt', 'updatedAt'];
    }
}