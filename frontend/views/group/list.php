<?php

/* @var $this yii\web\View */
/* @var $groups common\models\Group[] */

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('lbl', 'Organizations list');
?>
<div class="site-list">
    <h1><?= $this->title ?></h1>
    <?php foreach ($groups as $group): ?>
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
    <?php endforeach; ?>
</div>
