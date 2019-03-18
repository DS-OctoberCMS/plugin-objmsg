<?php

namespace Wbry\ObjMsg\Classes\Traits;

use Wbry\ObjMsg\Models\Message as MessageModel;

/**
 * Trait RelationMessages
 *
 * @package Wbry\ObjMsg\Classes\Traits
 * @author Diamond Systems
 */
trait RelationMessages
{
    public $relationConfig = '$/wbry/objmsg/controllers/messages/config_relation.yaml';

    /*
     * Renders
     */
    public function relationRenderView($field = null)
    {
        return $this->relationMessageRenderView($field = null);
    }

    /*
     * Ajax
     */

    public function onRelationManageCreate()
    {
        return $this->modelRelationMessageExtend('manageCreate');
    }

    public function onRelationManageUpdate()
    {
        return $this->modelRelationMessageExtend('manageUpdate');
    }

    public function onRelationButtonDelete()
    {
        return $this->modelRelationMessageExtend('btnDelete', false);
    }

    public function onRelationButtonRemove()
    {
        return $this->modelRelationMessageExtend('btnRemove', false);
    }

    /*
     * Helpers
     */

    protected function filterObjId($objId)
    {
        return $objId;
    }

    protected function getObjId()
    {
        $post_id = empty($this->params[0]) ? 0 : (int)$this->params[0];
        if ($post_id && (! is_numeric($post_id) || $post_id < 1))
            $post_id = 0;

        return $this->filterObjId($post_id);
    }

    protected function modelRelationMessageExtend($eventAction = null, $isObjId = true)
    {
        $objId = $isObjId ? $this->getObjId() : 0;

        MessageModel::extend(function($model) use ($objId, $eventAction)
        {
            if ($objId)
            {
                $model->bindEvent('model.beforeValidate', function() use ($model, $objId) {
                    $model->post_id = $objId;
                });
            }
            if ($eventAction === 'manageCreate')
            {
                $model->bindEvent('model.beforeCreate', function() use ($model) {
                    $model->is_admin = 1;
                });
            }
            $model::$isMessageMutator = true;
        });

        switch ($eventAction)
        {
            case 'manageCreate': return parent::onRelationManageCreate();
            case 'manageUpdate': return parent::onRelationManageUpdate();
            case 'btnDelete':    return parent::onRelationButtonDelete();
            case 'btnRemove':    return parent::onRelationButtonRemove();
        }
    }

    protected function relationMessageRenderView($field = null)
    {
        $relationModel = $this->relationModel;
        if ($relationModel instanceof MessageModel)
            $relationModel::$isMessageMutator = true;

        return parent::relationRenderView($field);
    }
}