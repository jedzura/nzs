<?php

use kartik\grid\GridView;
use yii\bootstrap\Html;

/** @var $this yii\web\View */
/** @var $users common\models\User[] */
?>
<h1><?= Yii::t('lbl', 'Users') ?></h1>
<div class="row">
    <?= $this->render('/panel/_menu'); ?>
    <div class="col-xs-12 col-sm-8 col-md-9">
        <article>
            <?= GridView::widget([
                'dataProvider' => $users,
                'summary' => false,
                'columns' => [
                    'id',
                    'username',
                    'firstname',
                    'lastname',
                    'email',
                    [
                        'attribute' => 'status',
                        'value' => function($model) {
                            return $model->status ? 'Active' : 'Inactive';
                        }
                    ]
                ]
            ]) ?>
        </article>
    </div>
</div>
