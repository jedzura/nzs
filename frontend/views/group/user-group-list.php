<?php

use kartik\grid\GridView;
use yii\bootstrap\Html;

/** @var $this yii\web\View */
/** @var $ownerGroups common\models\Group[] */
/** @var $userGroups common\models\Group[] */
?>
<h1><?= Yii::t('lbl', 'Organizations') ?></h1>
<div class="row">
    <?= $this->render('/panel/_menu'); ?>
    <div class="col-xs-12 col-sm-8 col-md-9">
        <article>
            <h4>Organizacje, którymi zarządzasz</h4>
            <?= GridView::widget([
                'dataProvider' => $ownerGroups,
                'summary' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format' => 'html',
                        'label' => Yii::t('lbl', 'Organization name'),
                        'value' => function($model)
                        {
                            return Html::a($model->name, ['group/index', 'url' => $model->url]);
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}'
                    ]
                ]
            ]) ?>
        </article>
        <article>
            <h4>Organizacje, których jesteś członkiem</h4>
            <?= GridView::widget([
                'dataProvider' => $userGroups,
                'summary' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format' => 'html',
                        'label' => Yii::t('lbl', 'Organization name'),
                        'value' => function($model)
                        {
                            return Html::a($model->name, ['group/index', 'url' => $model->url]);
                        }
                    ],
                ]
            ]) ?>
        </article>
    </div>
</div>
