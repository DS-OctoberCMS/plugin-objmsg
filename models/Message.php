<?php

namespace Wbry\ObjMsg\Models;

use Auth;
use Model;

/**
 * Class Message
 *
 * @package Wbry\ObjMsg\Models
 * @author Diamond Systems
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $timestamps = true;

    public static $isMessageMutator = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'wbry_objmsg_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'message',
        'is_admin',
        'is_view',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'message' => 'required|between:2,1000',
        'post_id' => 'required|integer|min:0',
    ];

    /*
     * Mutator
     */

    public function getMessageAttribute($value)
    {
        if (self::$isMessageMutator)
            return str_limit(strip_tags($value), 100);
        return $value;
    }

    /*
     * Scopes
     */

    public function scopeNewMsg($query)
    {
        return $query->where('is_view', '<', 1);
    }

    public function scopeNewMsgUser($query)
    {
        return $query->where('is_view', '<', 1)->where('is_admin', '<', 1);
    }

    public function scopeNewMsgAdmin($query)
    {
        return $query->where('is_view', '<', 1)->where('is_admin', '>', 0);
    }

    public function scopeObjCurrentUser($query)
    {
        return $query->where('post_id', '=', (Auth::check() ? Auth::getUser()->id : 0));
    }
}
