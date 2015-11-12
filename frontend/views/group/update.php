<?php

use common\models\City;
use common\models\University;
use Zelenin\yii\widgets\Summernote\Summernote;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/** @var $this yii\web\View */
/** @var $userGroups common\models\Group[] */

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
<h1>Edycja organizacji</h1>
<div class="row">
    <?= $this->render('/panel/_menu'); ?>
    <div class="col-xs-12 col-sm-8 col-md-9">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <article>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'logo')->fileInput() ?>
                    </div>
                    <div class="col-xs-6 text-right">
                        <?php
                            if ($model->has_logo) {
                                echo Html::img('/logo/' . $model->id . '.jpg', ['style' => 'max-width: 100%']);
                            }
                        ?>
                    </div>
                </div>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'city_id')->dropDownList($cities, ['prompt' => '']) ?>
                <?= $form->field($model, 'university_id')->dropDownList($universities, ['prompt' => '']) ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'tag')->textarea(['class' => 'form-control tags'])->label(Yii::t('lbl', 'Interests') . ' <small>(' . Yii::t('lbl', 'Tags') . ')</small>')->hint(Yii::t('msg', 'Write some word then click enter to add a tag.')) ?>
                <?= $form->field($model, 'short')->textarea() ?>
                <?= $form->field($model, 'content')->widget(Summernote::className(), [
                    'clientOptions' => [
                        'lang' => Yii::$app->language,
                        'fontNames' => ['Arial', 'Courier', 'Courier New', 'Lato']
                    ]
                ]) ?>
            </article>
            <div class="row">
                <div class="col-xs-12">
                    <?= Html::submitButton(Yii::t('btn', 'Save'), ['class' => 'btn pull-right']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
