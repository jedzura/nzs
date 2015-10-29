<?php

/** @var $this yii\web\View */
?>
<h1><?= Yii::t('lbl', 'User panel') ?></h1>
<div class="row">
    <?= $this->render('/panel/_menu'); ?>
    <div class="col-xs-12 col-sm-8 col-md-9">
        <article>
            <h4><?= Yii::t('lbl', 'Welcome') ?> <?= Yii::$app->user->getIdentity()->firstname . ' ' . Yii::$app->user->getIdentity()->lastname ?></h4>
        </article>
    </div>
</div>
