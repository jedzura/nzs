<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

}
