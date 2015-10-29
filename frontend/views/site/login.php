<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('lbl', 'Login form');
?>
<div class="site-login">
    <h1 class="text-center"><?= $this->title ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div class="col-xs-12">
            <div class="row">
                <div class="black-panel col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <?= $form->field($model, 'username', ['inputOptions' => ['autofocus' => 'autofocus']]) ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <div style="margin:1em 0">
                        <?= Yii::t('msg', 'If you forgot your password you can {link}.', ['link' => Html::a(Yii::t('msg', 'reset it'), ['site/request-password-reset'])]) ?>
                    </div>
                    <div style="margin:1em 0">
                        <?= Yii::t('msg', 'Nie masz jeszcze konta? {link}.', ['link' => Html::a(Yii::t('msg', 'Zarejestruj się tutaj!'), ['site/'])]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="row">
                        <?= Html::submitButton(Yii::t('btn', 'Sign in'), ['class' => 'btn pull-right', 'name' => 'login-button']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
