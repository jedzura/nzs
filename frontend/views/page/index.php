<?php

/* @var $this yii\web\View */
/* @var $model common\models\Page */

use yii\bootstrap\Html;

$this->title = Html::encode($model->title);
?>
<div class="site-page">
    <h1><?= $this->title ?></h1>

    <article>
        <?= $model->content ? $model->content : 'Brak treści' ?>
    </article>
</div>
