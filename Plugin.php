<?php

namespace Wbry\ObjMsg;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Wbry\ObjMsg\Models\Settings as SettingsModel;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'wbry.objmsg::lang.plugin.name',
            'description' => 'wbry.objmsg::lang.plugin.description',
            'author'      => 'Weberry, Diamond Systems',
            'icon'        => 'icon-send-o'
        ];
    }

    public function registerPermissions()
    {
        return [
            'wbry.objmsg.settings' => [
                'tab'   => 'wbry.objmsg::lang.plugin.name',
                'label' => 'wbry.objmsg::lang.permissions.settings',
            ],
            'wbry.objmsg.msg' => [
                'tab'   => 'wbry.objmsg::lang.plugin.name',
                'label' => 'wbry.objmsg::lang.permissions.msg',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'wbry.objmsg::lang.plugin.name',
                'description' => 'wbry.objmsg::lang.models.settings.settings_desc',
                'category'    => SettingsManager::CATEGORY_CUSTOMERS,
                'icon'        => 'icon-send-o',
                'class'       => 'Wbry\ObjMsg\Models\Settings',
                'permissions' => [
                    'wbry.objmsg.settings'
                ]
            ]
        ];
    }

    public function registerNavigation()
    {
        if (! (bool)SettingsModel::get('control_is_user_obj'))
            return [];

        $mainUrl = Backend::url('wbry/objmsg/users');

        return [
            'wbry.objmsg' => [
                'label' => 'wbry.objmsg::lang.navigation.wbry_objmsg',
                'icon'  => 'icon-send-o',
                'url'   => $mainUrl,
                'permissions' => [
                    'wbry.objmsg.msg'
                ],
                'sideMenu' => [
                    'wbry.objmsg.users' => [
                        'label' => 'wbry.objmsg::lang.navigation.wbry_objmsg_users',
                        'icon'  => 'icon-users',
                        'url'   => $mainUrl,
                        'permissions' => [
                            'wbry.objmsg.msg'
                        ],
                    ]
                ],
            ],
        ];
    }

    public function registerComponents()
    {
        return [
//            Components\AddObjMsg::class  => 'addObjMsg',
//            Components\ObjMsgList::class => 'objMsgList',
//            Components\ObjMsgMessages::class => 'objMsgMessages',
        ];
    }

    public function registerMailTemplates()
    {
        return [
//            'wbry.objmsg::mail.add_app_reg_admin'     => 'Add new ObjMsg (send admin)',
//            'wbry.objmsg::mail.add_app_reg_user'      => 'Add new ObjMsg (send user)',
//            'wbry.objmsg::mail.change_app_reg_status' => 'Change status',
//            'wbry.objmsg::mail.send_app_reg_message'  => 'New ObjMsg msg (user)',
//            'wbry.objmsg::mail.send_app_reg_message_admin'  => 'New ObjMsg msg (admin)',
        ];
    }
}