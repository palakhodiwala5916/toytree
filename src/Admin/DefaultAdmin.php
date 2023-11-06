<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;

abstract class DefaultAdmin extends AbstractAdmin
{

    protected function addListActions(ListMapper $list, array $actions = ['show', 'edit'])
    {
        $actionsConfig = [];

        if (in_array('show', $actions)) {
            $actionsConfig['show'] = [];
        }
        if (in_array('edit', $actions)) {
            $actionsConfig['edit'] = [];
        }
        if (in_array('delete', $actions)) {
            $actionsConfig['delete'] = [];
        }

        if (count($actionsConfig) > 0) {
            $list->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => $actionsConfig,
            ]);
        }
    }

}
