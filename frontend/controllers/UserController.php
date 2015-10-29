<?php

namespace frontend\controllers;

use common\models\User;
use common\widgets\Alert;
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
        /** @var User $model */
        $model = User::findOne(Yii::$app->user->getIdentity()->id);
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->newPassword) {
                $model->setPassword($model->newPassword);
            }
            if ($model->save()) {
                Alert::add(Yii::t('msg', 'Operation completed successfully.'));
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

}
