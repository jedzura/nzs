<?php

use common\models\City;
use common\models\Tag;
use common\models\University;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$cities = ArrayHelper::map(City::find()->orderBy('name')->all(), 'id', 'name');
$tags = ArrayHelper::map(Tag::find()->orderBy('name')->all(), 'id', 'name');
$universities = ArrayHelper::map(University::find()->orderBy('name')->all(), 'id', 'name');
?>

<h1><?= Yii::t('lbl', 'Find organizations') ?></h1>
<?php $form = ActiveForm::begin(); ?>
    <article class="searchbox">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5">
                <?= $form->field($model, 'name', [
                    'inputOptions' => [
                        'autofocus' => 'autofocus',
                        'placeholder' => Yii::t('lbl', 'Name')
                    ]
                ])->textInput()->label(false) ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-5">
                <?= $form->field($model, 'city_id')->dropDownList($cities, ['prompt' => Yii::t('lbl', 'City')])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5">
                <?= $form->field($model, 'university_id')->dropDownList($universities, ['prompt' => Yii::t('lbl', 'University')])->label(false) ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-5">
                <?= $form->field($model, 'tag_id')->dropDownList($tags, ['prompt' => Yii::t('lbl', 'Interests')])->label(false) ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2">
                <div class="form-group">
                    <?= Html::submitButton('Szukaj ' . Html::icon('search'), ['class' => 'btn btn-fullwidth btn-orange']) ?>
                </div>
            </div>
        </div>
    </article>
<?php ActiveForm::end() ?>

<div class="search-results">
    <?php
        if (is_array($results)) {
            if (empty($results)) {
                echo '<article>' . Yii::t('msg', 'No results') . '</article>';
            } else {
                foreach ($results as $group) :
            ?>
                    <article class="list-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <?= $group->has_logo ? Html::img('/logo/' . $group->id . '.jpg', ['alt' => $group->name]) : '' ?>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <h4><?= $group->name ?></h4>
                                <p class="text-blue"><?= $group->short ?></p>
                                <p class="hash-tags">
                                    <?php
                                        if ($group->tags) {
                                            echo '#' . implode(' #', ArrayHelper::map($group->tags, 'id', 'name'));
                                        }
                                    ?>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <?= Html::a(Yii::t('btn', 'See<br>page'), ['group/index', 'url' => $group->url], ['class' => 'button-orange']) ?>
                            </div>
                        </div>
                    </article>
            <?php endforeach;
            }
        }
    ?>
</div>
