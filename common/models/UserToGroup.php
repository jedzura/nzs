<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $user_id
 * @property integer $group_id
 * @property integer $can_edit
 * @property integer $group_admin
 * @property integer $status 1 - active, 0 - waiting for accept/decline
 *
 */
class UserToGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_to_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'group_id', 'can_edit', 'group_admin'], 'required'],
            [['user_id', 'group_id', 'can_edit', 'group_admin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('lbl', 'User'),
            'group_id' => Yii::t('lbl', 'Group'),
            'can_edit' => Yii::t('lbl', 'Can edit'),
            'group_admin' => Yii::t('lbl', 'Group admin'),
            'status' => Yii::t('lbl', 'Status'),
        ];
    }
}
