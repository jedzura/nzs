<?php

namespace frontend\controllers;

use common\models\Page;
use yii\bootstrap\Alert;

class PageController extends \yii\web\Controller
{
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex($url)
    {
        $model = Page::findOne(['url' => $url]);
        if (!$model) {
            Alert::add('Strona nie została znaleziona', Alert::TYPE_ERROR);
            return $this->goHome();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
