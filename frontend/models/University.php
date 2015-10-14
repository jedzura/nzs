<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "university".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 *
 * @property Group[] $groups
 * @property City $city
 */
class University extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'university';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'name'], 'required'],
            [['id', 'city_id'], 'integer'],
            [['name'], 'string', 'max' => 256],
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
            'city_id' => Yii::t('lbl', 'City ID'),
            'name' => Yii::t('lbl', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['university_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
