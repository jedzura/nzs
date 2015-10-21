<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('lbl', 'Registration');
?>
<div class="site-signup">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <div class="col-xs-12">
            <div class="row">
                <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <?= $form->field($model, 'username')->textInput() ?>
                    <?= $form->field($model, 'email')->textInput() ?>
                    <?= $form->field($model, 'firstname')->textInput() ?>
                    <?= $form->field($model, 'lastname')->textInput() ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="row">
                        <?= Html::submitButton(Yii::t('btn', 'Sign up'), ['class' => 'btn pull-right', 'name' => 'signup-button']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
