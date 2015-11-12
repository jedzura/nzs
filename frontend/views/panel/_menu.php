<?php

use yii\bootstrap\Nav;

?>
<div class="col-xs-12 col-sm-4 col-md-3">
    <?= Nav::widget([
        'options' => [
            'class' => 'panel-menu',
        ],
        'items' => [
            [
                'label' => Yii::t('lbl', 'Profile'),
                'url' => ['user/update']
            ],
//            [
//                'label' => Yii::t('lbl', 'Users'),
//                'url' => ['user/list'],
//                'visible' => Yii::$app->user->getIdentity()->is_admin
//            ],
//            [
//                'label' => Yii::t('lbl', 'Messages'),
//                'url' => ['message/list']
//            ],
            [
                'label' => Yii::t('lbl', 'Organizations'),
                'url' => ['group/user-group-list']
            ],
//            [
//                'label' => Yii::t('lbl', 'Pages'),
//                'url' => ['page/list'],
//                'visible' => Yii::$app->user->getIdentity()->is_admin
//            ],
//            [
//                'label' => Yii::t('lbl', 'Settings'),
//                'url' => ['settings/index'],
//                'visible' => Yii::$app->user->getIdentity()->is_admin
//            ]
        ]
    ]) ?>
</div>
