<?php

namespace Wbry\ObjMsg;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Wbry\ObjMsg\Models\Settings as SettingsModel;

class Plugin extends PluginBase
{
    public $require = [
        'RainLab.User',
        'Wbry.Base',
    ];

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
                'description' => 'wbry.objmsg::lang.models.settings.desc',
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
            Components\Messages::class  => 'messages',
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'wbry.objmsg::mail.send_message_user'  => 'New msg (user)',
            'wbry.objmsg::mail.send_message_admin' => 'New msg (admin)',
        ];
    }
}
