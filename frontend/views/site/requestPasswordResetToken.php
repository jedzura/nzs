<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('lbl', 'Request password reset');
?>
<div class="site-request-password-reset">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <div class="col-xs-12">
            <div class="row">
                <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <p><?= Yii::t('msg', 'Please fill out your email. A link to reset password will be sent there.') ?></p>
                    <?= $form->field($model, 'email') ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="row">
                        <?= Html::submitButton(Yii::t('btn', 'Send'), ['class' => 'btn pull-right']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
