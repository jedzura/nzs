<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model common\models\User */
?>
<h1><?= Yii::t('lbl', 'Profile') ?></h1>
<div class="row">
    <?= $this->render('/panel/_menu'); ?>
    <div class="col-xs-12 col-sm-8 col-md-9">
        <?php $form = ActiveForm::begin() ?>
            <article>
                <?= $form->field($model, 'firstname')->textInput() ?>
                <?= $form->field($model, 'lastname')->textInput() ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'facebook')->textInput() ?>
                <?= $form->field($model, 'twitter')->textInput() ?>
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
            </article>
            <div class="row">
                <div class="col-xs-12">
                    <?= Html::submitButton(Yii::t('btn', 'Save'), ['class' => 'btn pull-right']) ?>
                </div>
            </div>
        <?php $form->end() ?>
    </div>
</div>
