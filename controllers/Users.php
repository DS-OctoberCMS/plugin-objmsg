<?php

namespace Wbry\ObjMsg\Controllers;

use Lang;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Redirect;
use Wbry\ObjMsg\Models\User as UserModel;
use Wbry\ObjMsg\Models\Settings as SettingsModel;

/**
 * Users Back-end Controller
 *
 * @package Wbry\ObjMsg\Controllers
 * @author Diamond Systems
 */
class Users extends Controller
{
    use \Wbry\ObjMsg\Classes\Traits\RelationMessages;

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'wbry.objmsg.msg'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Wbry.ObjMsg', 'wbry.objmsg', 'wbry.objmsg.users');
    }

    public function run($action = null, $params = [])
    {
        if (! (bool)SettingsModel::get('control_is_user_obj'))
            return Redirect::to(Backend::url('/'));

        return parent::run($action, $params);
    }

    /*
     * Filters
     */

    public function listExtendQuery($query)
    {
        $query->whereHas('throttle', function ($query) {
            $query->where('is_banned', '<', 1);
        });
    }

    public function filterObjId($objId)
    {
        return UserModel::getUserOnId($objId) ? $objId : 0;
    }

    /*
     * Actions
     */

    public function messages($userId=0)
    {
        # Check user
        if (! is_numeric($userId) || $userId < 1 || ! ($user = UserModel::userOnId($userId)->first()))
            $this->fatalError = Lang::get('wbry.objmsg::lang.controllers.users.error.no_user');
        elseif ($user->isBanned())
            $this->fatalError = Lang::get('wbry.objmsg::lang.controllers.users.error.ban_user');

        # Title
        if ($user)
            $this->pageTitle = Lang::get('wbry.objmsg::lang.controllers.users.breadcrumbs', ['email' => $user->email]);
        else
            $this->pageTitle = Lang::get('wbry.objmsg::lang.controllers.users.breadcrumbs_empty');

        # init form
        $this->addCss('/plugins/wbry/objmsg/assets/css/admin/message-list.css');
        $this->bodyClass = 'user-message-list';
        $this->initForm($user);
    }
}
