<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('lbl', 'Reset password');
?>
<div class="site-reset-password">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <div class="col-xs-12">
            <div class="row">
                <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <p><?= Yii::t('msg', 'Please choose your new password:') ?></p>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="row">
                        <?= Html::submitButton(Yii::t('btn', 'Save'), ['class' => 'btn pull-right']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
