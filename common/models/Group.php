<?php

namespace common\models;

use common\models\Tag;
use common\models\University;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property integer $university_id
 * @property string $name
 * @property string $short
 * @property string $content
 * @property string $url
 * @property string $email
 * @property string $has_logo
 * @property integer $status
 *
 * @property University $university
 * @property TagToGroup[] $tagToGroups
 * @property UserToGroup[] $userToGroups
 * @property User[] $users
 */
class Group extends \yii\db\ActiveRecord
{
    public $tag;

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
            [['city_id', 'university_id', 'name'], 'required'],
            [['city_id', 'university_id'], 'integer'],
            [['short', 'content'], 'string'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 64],
            [['short', 'name', 'url'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lbl', 'ID'),
            'city_id' => Yii::t('lbl', 'City'),
            'university_id' => Yii::t('lbl', 'University'),
            'name' => Yii::t('lbl', 'Name'),
            'short' => Yii::t('lbl', 'Short description'),
            'content' => Yii::t('lbl', 'Content'),
            'url' => Yii::t('lbl', 'Url'),
            'has_logo' => Yii::t('lbl', 'Logo'),
            'email' => Yii::t('lbl', 'Email'),
            'status' => Yii::t('lbl', 'Status'),
            'tag' => Yii::t('lbl', 'Interests'),
        ];
    }

    public function saveGroup()
    {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $this->name);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        $this->url = $clean;

        if (!$this->save()) {
            return false;
        }
        $tags = explode(',', str_replace(['[', ']', '\''], ['', '', ''], $this->tag));
        foreach($tags as $tag) {
            $newTag = Tag::findOne(['name' => strtolower($tag)]);
            if (!$newTag) {
                $newTag = new Tag();
                $newTag->name = strtolower($tag);
                if ($newTag->save()) {
                    $tagToGroup = new TagToGroup();
                    $tagToGroup->tag_id = $newTag->id;
                    $tagToGroup->group_id = $this->id;
                    $tagToGroup->save();
                }
            } else {
                $tagToGroup = new TagToGroup();
                $tagToGroup->tag_id = $newTag->id;
                $tagToGroup->group_id = $this->id;
                $tagToGroup->save();
            }
        }
        $userToGroup = new UserToGroup();
        $userToGroup->user_id = Yii::$app->user->getIdentity()->id;
        $userToGroup->group_id = $this->id;
        $userToGroup->can_edit = 1;
        $userToGroup->group_admin = 1;
        if ($userToGroup->save()) {
            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
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

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable(TagToGroup::tableName(), ['group_id' => 'id'])->orderBy('name');
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
