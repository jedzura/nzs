<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('/img/logo.png', ['title' => 'Działaj z nami']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Historia NZS', 'url' => ''],
        ['label' => 'Lista organizacji', 'url' => ''],
        ['label' => 'Znajdź organizację', 'url' => ''],
        ['label' => 'Konkurs', 'url' => ''],
    ];

    if (Yii::$app->user->isGuest) {
        $subMenu = [
            [
                'label' => Yii::t('btn', 'Register'),
                'url' => ['site/signup'],
            ],
            [
                'label' => Yii::t('btn', 'Login'),
                'url' => ['site/login'],
            ],
        ];
    } else {
        $subMenu = [
            [
                'label' => Yii::t('btn', 'Settings'),
                'url' => ['settings/index'],
            ],
            [
                'label' => Yii::t('btn', 'Logout ({username})', ['username' => Yii::$app->user->identity->username]),
                'url' => ['site/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ],
        ];
    }

    $menuItems[] = [
        'label' => Html::img('/img/profile.png'),
        'url' => '',
        'items' => $subMenu
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container black-block">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <?= Html::img('/img/logo_kultura_dostepna.png') ?>
            </div>
            <div class="col-xs-12 col-sm-6 text-center">
                Dofinansowano ze środków narodowego centrum kultury w ramach programu kultura - interwencje 2015
            </div>
            <div class="col-xs-12 col-sm-3">
                <?= Html::img('/img/logo_nzs.png') ?>
            </div>
        </div>
    </div>
    <div class="container text-center info-line">
        <?= Html::a('Regulamin portalu','') ?> /
        <?= Html::a('Polityka prywatności', '') ?> /
        <?= Html::a('Kontakt', ['site/contact']) ?> /
        Copyright &copy; Niezależne Zrzeszenie Studentów /
        Wykonanie: <?= Html::a('Subinet', 'http://subinet.pl') ?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
