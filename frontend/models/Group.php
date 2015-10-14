<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property integer $university_id
 * @property string $name
 * @property string $content
 * @property string $url
 *
 * @property University $university
 * @property TagToGroup[] $tagToGroups
 * @property UserToGroup[] $userToGroups
 * @property User[] $users
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'university_id', 'name', 'content', 'url'], 'required'],
            [['id', 'university_id'], 'integer'],
            [['content'], 'string'],
            [['name', 'url'], 'string', 'max' => 256],
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
            'university_id' => Yii::t('lbl', 'University ID'),
            'name' => Yii::t('lbl', 'Name'),
            'content' => Yii::t('lbl', 'Content'),
            'url' => Yii::t('lbl', 'Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUniversity()
    {
        return $this->hasOne(University::className(), ['id' => 'university_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagToGroups()
    {
        return $this->hasMany(TagToGroup::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserToGroups()
    {
        return $this->hasMany(UserToGroup::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_to_group', ['group_id' => 'id']);
    }
}
