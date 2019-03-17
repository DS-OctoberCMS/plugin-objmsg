<?php

namespace Wbry\ObjMsg\Controllers;

use Lang;
use Mail;
use Flash;
use Request;
use Validator;
use Exception;
use BackendMenu;
use ValidationException;
use ApplicationException;
use Backend\Classes\FormField;
use Backend\Classes\Controller;
use Backend\FormWidgets\RichEditor;
use Wbry\ObjMsg\Models\ObjMsg as ObjMsgModel;
use Wbry\ObjMsg\Models\Message as MessageModel;
use Wbry\ObjMsg\Models\Settings as SettingsModel;

/**
 * Class Messages
 *
 * @package Wbry\ObjMsg\Controllers
 * @author Diamond Systems
 */
class Messages extends Controller
{
//    protected $defaultFormToolbarButtons = [
//        'paragraphFormat',
//        'quote',
//        'bold',
//        'italic',
//        'align',
//        'formatOL',
//        'formatUL',
//        'insertTable',
//        'insertLink',
//        'insertImage',
//        'insertFile',
//        'insertHR',
//        'fullscreen',
//        'html'
//    ];

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

//    /*
//     * Construct
//     */
//
//    public function __construct()
//    {
//        parent::__construct();
//        BackendMenu::setContext('Wbry.ObjMsg', 'wbry.objmsg', 'wbry.objmsg.list');
//
//        $this->loadWidgets();
//        $this->addAssets();
//    }
//
//    public function loadWidgets()
//    {
//        # RichEditor
//        foreach (['changeStatusMsg','sendNewMsg','editMsg'] as $name)
//        {
//            $formField = new FormField($name, $name);
//            $formField->value = '';
//            $richEditor = new RichEditor($this, $formField);
//            $richEditor->alias = $name;
//            $richEditor->toolbarButtons = $this->defaultFormToolbarButtons;
//            $richEditor->bindToController();
//        }
//    }
//
//    public function addAssets()
//    {
//        # Custom
//        $this->addJs('/plugins/wbry/objmsg/assets/js/admin/app-reg.js');
//        $this->addCss('/plugins/wbry/objmsg/assets/css/admin/app-reg.css');
//
//        # framework extras
//        $this->addJs('/modules/system/assets/js/framework.extras.js');
//        $this->addCss('/modules/system/assets/css/framework.extras.css');
//    }
//
//    /*
//     * Actions
//     */
//
//    public function getCntUserNewMsg($postId)
//    {
//        return MessageModel::newMsgUser()->where('post_id', $postId)->count();
//    }
//
//    /*
//     * Ajax
//     */
//
//    public function onChangeStatus()
//    {
//        try {
//            /*
//             * Validate
//             */
//            $data   = post();
//            $objMsg = $this->getObjMsg();
//
//            $langStatus = Lang::get('wbry.objmsg::lang.controllers.validations.status');
//
//            $validation = Validator::make($data,[
//                'status'          => 'required|integer|min:1',
//                'changeStatusMsg' => 'required|between:2,1000',
//            ], [
//                'status.required' => $langStatus,
//                'status.integer'  => $langStatus,
//                'status.min'      => $langStatus,
//            ]);
//            $validation->setAttributeNames([
//                'changeStatusMsg' => Lang::get('wbry.objmsg::lang.controllers.attribute.changeStatusMsg')
//            ]);
//
//            if ($validation->fails())
//                throw new ValidationException($validation);
//
//            $postData = [
//                'id'      => (int)array_get($data, 'postId'),
//                'status'  => (int)array_get($data, 'status'),
//                'message' => array_get($data, 'changeStatusMsg'),
//            ];
//
//            if ($objMsg->application_status == $postData['status'])
//                throw new ApplicationException(Lang::get('wbry.objmsg::lang.controllers.validations.status_and'));
//
//            /*
//             * Change status
//             */
//
//            $objMsg->application_status = $postData['status'];
//            $objMsg->save();
//
//            Flash::success(Lang::get('wbry.objmsg::lang.controllers.msg.success_change_status'));
//
//            /*
//             * Information
//             */
//
//            # save message
//            $status_name = $objMsg->getStatus($postData['status'])->name;
//            $addMsg = '<h2 class="change-status">'. Lang::get('wbry.objmsg::lang.controllers.change_status.change').
//                ' - "'. $status_name .'"</h2>';
//
//            MessageModel::create([
//                'post_id'  => $objMsg->id,
//                'message'  => $addMsg . $postData['message'],
//                'is_admin' => 1,
//                'is_view'  => 0,
//            ]);
//
//            # mail send user
//            $user = $objMsg->user;
//            if ($user)
//            {
//                $sendData = [
//                    'status'      => $status_name,
//                    'account_url' => url('/'),
//                    'message'     => $postData['message'],
//                ];
//                Mail::send('wbry.objmsg::mail.change_app_reg_status', $sendData, function($message) use ($user) {
//                    $message->to($user->email, $user->name);
//                });
//            }
//
//            return redirect()->intended(url()->current());
//
//        } catch (Exception $e){
//            Flash::error($e->getMessage());
//        }
//    }
//
//    public function onSendMessage()
//    {
//        try {
//            $message = $this->validateMessage('sendNewMsg');
//            $objMsg  = $this->getObjMsg();
//
//            # save message
//            MessageModel::create([
//                'post_id' => $objMsg->id,
//                'message' => $message,
//                'is_admin' => 1,
//                'is_view'  => 0,
//            ]);
//
//            Flash::success(Lang::get('wbry.objmsg::lang.controllers.msg.send_msg'));
//
//            # mail send user
//            if (SettingsModel::get('informer_is_new_msg') && $user = $objMsg->user)
//            {
//                $sendData = [
//                    'id'          => $objMsg->id,
//                    'account_url' => url('/'),
//                    'message'     => $message,
//                ];
//                Mail::send('wbry.objmsg::mail.send_app_reg_message', $sendData, function($message) use ($user) {
//                    $message->to($user->email, $user->name);
//                });
//            }
//
//            return redirect()->intended(url()->current());
//
//        } catch (Exception $e){
//            Flash::error($e->getMessage());
//        }
//    }
//
//    public function onEditMessage()
//    {
//        try {
//            $message = $this->validateMessage('editMsg');
//            $objMsg  = $this->getObjMsg();
//            $msgId   = (int)post('msgId');
//
//            if ($msgId < 1 || ! $msg = MessageModel::where('id', $msgId)->where('post_id', $objMsg->id)->first())
//                throw new ApplicationException(Lang::get('wbry.objmsg::lang.controllers.validations.no_message'));
//
//            # save message
//            $msg->message = $message;
//            $msg->save();
//
//            Flash::success(Lang::get('wbry.objmsg::lang.controllers.msg.edit_msg'));
//
//        } catch (Exception $e){
//            Flash::error($e->getMessage());
//        }
//    }
//
//    public function onGetMessage()
//    {
//        try {
//            list($postId, $msg) = $this->checkMessage();
//
//            if (! $msg->is_admin && ! $msg->is_view)
//            {
//                $msg->is_view = 1;
//                $msg->save();
//            }
//
//            $retData = [];
//
//            if ((bool)post('isEdit', 0))
//            {
//                $retData['msg'] = $msg->message;
//                $retData['msgId'] = $msg->id;
//            }
//            else
//                $retData['#ajaxResultMessageContent'] = $msg->message;
//
//            $retData['newMsgCnt'] = $this->getCntUserNewMsg($postId);
//
//            return $retData;
//
//        } catch (Exception $e){
//            Flash::error($e->getMessage());
//        }
//    }
//
//    public function onDelMessage()
//    {
//        try {
//            list($postId, $msg) = $this->checkMessage();
//
//            $msg->delete();
//
//            Flash::success(Lang::get('wbry.objmsg::lang.controllers.msg.del_msg'));
//
//            return [
//                'newMsgCnt' => $this->getCntUserNewMsg($postId)
//            ];
//
//        } catch (Exception $e){
//            Flash::error($e->getMessage());
//        }
//    }
//
//    /*
//     * Helpers
//     */
//
//    private function validateMessage($inputName)
//    {
//        $message = post($inputName);
//
//        $validation = Validator::make([
//            'sendNewMsg' => $message
//        ],[
//            'sendNewMsg' => 'required|between:2,1000'
//        ]);
//        $validation->setAttributeNames([
//            'sendNewMsg' => Lang::get('wbry.objmsg::lang.controllers.attribute.inputMsg')
//        ]);
//
//        if ($validation->fails())
//            throw new ValidationException($validation);
//
//        return $message;
//    }
//
//    private function checkMessage()
//    {
//        $postId  = (int)post('post_id');
//        $msgId   = (int)post('msg_id');
//        $errLang = Lang::get('wbry.objmsg::lang.controllers.validations.no_message');
//
//        if ($postId < 1 || $msgId < 1 || ! $msg = MessageModel::where('id', $msgId)->where('post_id', $postId)->first())
//            throw new ApplicationException($errLang);
//
//        return [
//            $postId,
//            $msg
//        ];
//    }
//
//    private function getObjMsg()
//    {
//        $postId     = post('postId');
//        $langPostId = Lang::get('wbry.objmsg::lang.controllers.validations.postId');
//
//        $validation = Validator::make([
//            'postId' => $postId
//        ], [
//            'postId' => 'required|integer|min:0'
//        ], [
//            'postId.required' => $langPostId,
//            'postId.integer'  => $langPostId,
//            'postId.min'      => $langPostId,
//        ]);
//
//        if ($validation->fails())
//            throw new ValidationException($validation);
//
//        $objMsg = ObjMsgModel::where('id', $postId)->first();
//        if (! $objMsg)
//            throw new ApplicationException($langPostId);
//
//        return $objMsg;
//    }
}
