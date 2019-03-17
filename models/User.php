<?php

namespace Wbry\ObjMsg\Models;

use Model;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Throttle as ThrottleModel;

/**
 * User Model
 *
 * @package Wbry\ObjMsg\Models
 * @author Diamond Systems
 */
class User extends UserModel
{
    public $hasOne = [
        'throttle' => [
            ThrottleModel::class,
            'key' => 'user_id',
        ]
    ];

    public $hasMany = [
        'messages' => [
            'Wbry\ObjMsg\Models\Message',
            'key' => 'post_id'
        ]
    ];

    public static function getUserOnId($userId)
    {
        $user = self::IsActivated()->where('id', $userId)->first();
        if ($user->isBanned())
            return null;
        return $user;
    }

    /*
     * Scopes
     */

    public function scopeUserOnId($userId)
    {
        $this->IsActivated()->where('id', $userId);
    }
}
