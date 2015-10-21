<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $is_admin
 * @property string $username
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $facebook
 * @property string $twitter
 * @property integer $status
 * @property integer $created
 *
 * @property Role $role
 * @property UserToGroup[] $userToGroups
 * @property Group[] $groups
 */
class User extends ActiveRecord
{
    const
        STATUS_ACTIVE = 1,
        STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username', 'password', 'email', 'created_at', 'updated_at'], 'required'],
            [['id', 'is_admin', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password'], 'string', 'max' => 32],
            [['firstname', 'lastname', 'email', 'facebook', 'twitter'], 'string', 'max' => 64],
            [['id'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lbl', 'ID'),
            'is_admin' => Yii::t('lbl', 'Is Admin'),
            'username' => Yii::t('lbl', 'Username'),
            'password' => Yii::t('lbl', 'Password'),
            'firstname' => Yii::t('lbl', 'Firstname'),
            'lastname' => Yii::t('lbl', 'Lastname'),
            'email' => Yii::t('lbl', 'Email'),
            'facebook' => Yii::t('lbl', 'Facebook'),
            'twitter' => Yii::t('lbl', 'Twitter'),
            'status' => Yii::t('lbl', 'Status'),
            'created_at' => Yii::t('lbl', 'Created'),
            'updated_at' => Yii::t('lbl', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_to_group', ['user_id' => 'id']);
    }
}
