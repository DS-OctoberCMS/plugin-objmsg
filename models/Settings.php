<?php

namespace Wbry\ObjMsg\Models;

use Model;

/**
 * Class Settings
 *
 * @package Wbry\ObjMsg\Models
 * @author Diamond Systems
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'wbry_objmsg_settings';

    public $settingsFields = 'fields.yaml';
}
