<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $menu
 * @property integer $position
 * @property string $url
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'menu', 'position', 'url'], 'required'],
            [['id', 'menu', 'position'], 'integer'],
            [['content'], 'string'],
            [['title', 'url'], 'string', 'max' => 256],
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
            'title' => Yii::t('lbl', 'Title'),
            'content' => Yii::t('lbl', 'Content'),
            'menu' => Yii::t('lbl', 'Menu'),
            'position' => Yii::t('lbl', 'Position'),
            'url' => Yii::t('lbl', 'Url'),
        ];
    }
}
