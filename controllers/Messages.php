<?php

namespace Wbry\ObjMsg\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Class Messages
 *
 * @package Wbry\ObjMsg\Controllers
 * @author Diamond Systems
 */
class Messages extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'wbry.objmsg.msg'
    ];

    public function listExtendModel($model, $definition = null)
    {
        $model::$isMessageMutator = true;
        return $model;
    }

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Wbry.ObjMsg', 'wbry.objmsg', 'wbry.objmsg.list');
    }
}
