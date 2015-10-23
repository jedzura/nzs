<?php

/* @var $this yii\web\View */
/* @var $model common\models\Group */

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$this->title = Html::encode($model->name);
?>
<div class="site-group">
    <h1><?= $this->title ?></h1>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="group-info">
                <?php if ($model->has_logo): ?>
                <div class="group-logo">
                    <img src="/logo/<?= $model->id ?>.jpg" alt="<?= $model->name ?>">
                </div>
                <?php endif ?>
                <section>
                    <h4><?= Yii::t('lbl', 'Name') ?>:</h4>
                    <p><?= $model->name ?></p>
                </section>
                <section>
                    <h4><?= Yii::t('lbl', 'University') ?>:</h4>
                    <p><?= $model->university->name ?></p>
                </section>
                <section>
                    <h4><?= Yii::t('lbl', 'City') ?>:</h4>
                    <p><?= $model->city->name ?></p>
                </section>
                <?php if ($model->tags): ?>
                    <section>
                        <h4><?= Yii::t('lbl', 'Interests') ?>:</h4>
                        <?php
                            $tags = implode(', ', ArrayHelper::map($model->tags, 'id', 'name'));
                        ?>
                        <p><?= $tags ?></p>
                    </section>
                <?php endif; ?>
                <?php if ($model->email): ?>
                <?= Html::a(Html::icon('envelope') . Yii::t('btn', 'Write to us'), ['group/mail', 'url' => $model->url], ['class' => 'button-blue']) ?>
                <?php endif; ?>
                <?= Html::a(Html::icon('plus') . Yii::t('btn', 'Join'), ['group/join', 'url' => $model->url], ['class' => 'button-blue']) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9">
            <article>
                <?= $model->content ? $model->content : 'Brak opisu organizacji' ?>
            </article>
        </div>
    </div>

</div>
