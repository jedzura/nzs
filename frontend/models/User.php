<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $role_id
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
class User extends \yii\db\ActiveRecord
{
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
    public function rules()
    {
        return [
            [['id', 'role_id', 'username', 'password', 'firstname', 'lastname', 'email', 'created'], 'required'],
            [['id', 'role_id', 'status', 'created'], 'integer'],
            [['username', 'password'], 'string', 'max' => 32],
            [['firstname', 'lastname', 'email', 'facebook', 'twitter'], 'string', 'max' => 64],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lbl', 'ID'),
            'role_id' => Yii::t('lbl', 'Role ID'),
            'username' => Yii::t('lbl', 'Username'),
            'password' => Yii::t('lbl', 'Password'),
            'firstname' => Yii::t('lbl', 'Firstname'),
            'lastname' => Yii::t('lbl', 'Lastname'),
            'email' => Yii::t('lbl', 'Email'),
            'facebook' => Yii::t('lbl', 'Facebook'),
            'twitter' => Yii::t('lbl', 'Twitter'),
            'status' => Yii::t('lbl', 'Status'),
            'created' => Yii::t('lbl', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserToGroups()
    {
        return $this->hasMany(UserToGroup::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_to_group', ['user_id' => 'id']);
    }
}
