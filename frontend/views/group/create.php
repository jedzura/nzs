<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Group */

use common\models\City;
use common\models\University;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('lbl', 'Add your organization');

$cities = ArrayHelper::map(City::find()->orderBy('name')->all(), 'id', 'name');
$universities = [];
if ($model->city_id) {
    $universities = ArrayHelper::map(University::find()->where(['city_id' => $model->city_id])->all(), 'id', 'name');
}
$tags = '[]';
if ($model->tag) {
    $tags = $model->tag;
    $model->tag = null;
}
$this->registerJs("
    $('textarea.tags').textext({
        plugins: 'tags',
        tags: {
            items: " . $tags . "
        }
    });")
?>
<div class="site-signup">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12">
        <div class="row">
            <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'city_id')->dropDownList($cities, ['prompt' => '']) ?>
                <?= $form->field($model, 'university_id')->dropDownList($universities, ['prompt' => '']) ?>
                <?= $form->field($model, 'tag')->textarea(['class' => 'form-control tags'])->label(Yii::t('lbl', 'Interests') . ' <small>(' . Yii::t('lbl', 'Tags') . ')</small>')->hint(Yii::t('msg', 'Write some word then click enter to add a tag.')) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <div class="row">
                    <?php
                        if (Yii::$app->user->isGuest) {
                            echo Html::submitButton(Yii::t('btn', 'Next step') . ' &gt;', ['class' => 'btn pull-right']);
                        } else {
                            echo Html::submitButton(Yii::t('btn', 'Save'), ['class' => 'btn pull-right']);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
