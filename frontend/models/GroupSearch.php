<?php

namespace frontend\models;

use common\models\Group;
use common\models\Tag;
use common\models\TagToGroup;
use Yii;

/**
 *
 * @property integer $city_id
 * @property integer $university_id
 * @property string $name
 * @property string $tags
 *
 */
class GroupSearch extends \yii\db\ActiveRecord
{
    public
        $city_id,
        $name,
        $tag_id,
        $university_id;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('lbl', 'Name'),
            'city_id' => Yii::t('lbl', 'City'),
            'university_id' => Yii::t('lbl', 'University'),
            'tag_id' => Yii::t('lbl', 'Interests'),
        ];
    }

    public function search()
    {
        $query = Group::find();
        if ($this->name) {
            $query->andFilterWhere(['like', 'group.name', $this->name]);
        }
        if ($this->city_id) {
            $query->andFilterWhere(['group.city_id' => $this->city_id]);
        }
        if ($this->university_id) {
            $query->andFilterWhere(['group.university_id' => $this->university_id]);
        }
        if ($this->tag_id) {
            $query->leftJoin(TagToGroup::tableName() . ' AS tag', 'tag.group_id = group.id')->andFilterWhere(['tag.tag_id' => $this->tag_id]);
        }

        return $query->orderBy('group.name')->all();
    }
}
