<?php

namespace Wbry\ObjMsg\Components;

use Auth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Wbry\ObjMsg\Models\Message as MessageModel;
use Wbry\ObjMsg\Models\Settings as SettingsModel;

/**
 * Component Messages
 *
 * @package Wbry\ObjMsg\Components
 * @author Diamond Systems
 */
class Messages extends ComponentBase
{
    use \Wbry\Base\Classes\Traits\JqDataTables;

    public function componentDetails()
    {
        return [
            'name'        => 'wbry.objmsg::lang.components.messages.name',
            'description' => 'wbry.objmsg::lang.components.messages.desc',
        ];
    }

    public function defineProperties()
    {
        return [
            'indexMsgUrl' => [
                'title'       => 'wbry.objmsg::lang.components.messages.indexMsgUrl.title',
                'description' => 'wbry.objmsg::lang.components.messages.indexMsgUrl.desc',
                'type'        => 'dropdown',
                'default'     => '---',
            ],
            'createMsgUrl' => [
                'title'       => 'wbry.objmsg::lang.components.messages.createMsgUrl.title',
                'description' => 'wbry.objmsg::lang.components.messages.createMsgUrl.desc',
                'type'        => 'dropdown',
                'default'     => '---',
            ],
            'previewMsgUrl' => [
                'title'       => 'wbry.objmsg::lang.components.messages.previewMsgUrl.title',
                'description' => 'wbry.objmsg::lang.components.messages.previewMsgUrl.desc',
                'type'        => 'dropdown',
                'default'     => '---',
                'showExternalParam' => true,
            ],
            'msgIdParam' => [
                'title'       => 'wbry.objmsg::lang.components.messages.msgIdParam.title',
                'description' => 'wbry.objmsg::lang.components.messages.msgIdParam.desc',
                'type'        => 'string',
                'default'     => 'id',
                'showExternalParam' => true,
            ],
        ];
    }

    public function getIndexMsgUrlOptions()
    {
        return $this->getPagesList();
    }

    public function getCreateMsgUrlOptions()
    {
        return $this->getPagesList();
    }

    public function getPreviewMsgUrlOptions()
    {
        return $this->getPagesList();
    }

    private function getPagesList()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /*
     * Ajax
     */

    public function onUserMsgList()
    {
        MessageModel::$isMessageMutator = true;

        $retData = $this->resultJqDataTableQuery(MessageModel::make(), MessageModel::objCurrentUser(), [
            'id' => [
                'column' => 'id',
                'typeString' => false,
            ],
            'message' => [
                'column' => 'message',
            ],
            'is_view' => [
                'column' => 'is_view',
                'typeString' => false,
            ],
            'is_admin' => [
                'column' => 'is_admin',
                'typeString' => false,
            ],
            'created' => [
                'column' => 'created_at',
                'typeString' => false,
            ],
        ], [
            'objCurrentUser'
        ]);

        foreach ($retData['data'] as &$val)
        {
            if ($val['is_admin'] > 0 && $val['is_view'] < 1)
                $val['message'] = '<span class="badge badge-danger mr-2">*</span>'.$val['message'];
            $val['created'] = '<span class="msg-url-data" data-msg-id="'. $val['id'] .'">'. ($val['created'] ? $val['created']->format('Y-m-d H:i') : '-') .'</span>';
        }

        return response($retData);
    }

    /*
     * Helpers
     */

    public function getMsg($msgId)
    {
        if ($msgId && is_numeric($msgId) && $msgId > 0 && $msg = MessageModel::where('id', $msgId)->first())
        {
            $msg->is_view = 1;
            $msg->save();

            return $msg->message;
        }
        return '';
    }

    public function getMsgIdParam()
    {
        $idParamName = $this->property('msgIdParam');
        return [
            $idParamName => 'msg_id'
        ];
    }

    public function getNewMsgCnt($objId = null)
    {
        $objId = $this->getObjId($objId);
        return $objId ? MessageModel::newMsgAdmin()->where('post_id', $objId)->count() : 0;
    }

    public function msgCnt($objId = null)
    {
        $objId = $this->getObjId($objId);
        return $objId ? MessageModel::where('post_id', $objId)->count() : 0;
    }

    protected function getObjId($objId = null)
    {
        $objId = $objId ?: $this->property('obj_id');

        if (! $objId)
            return (Auth::check() && (bool)SettingsModel::get('control_is_user_obj')) ? Auth::getUser()->id : 0;
        elseif (! is_numeric($objId) || $objId < 1)
            return 0;
        else
            return $objId;
    }
}
