<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('lbl', 'Contact');
?>
<div class="site-contact">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
    <div class="col-xs-12">
        <div class="row">
            <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'subject') ?>
                <?= $form->field($model, 'body')->textArea() ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <div class="row">
                    <?= Html::submitButton(Yii::t('btn', 'Send'), ['class' => 'btn pull-right', 'name' => 'contact-button']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
