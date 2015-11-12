<?php

namespace frontend\controllers;

use common\models\User;
use common\widgets\Alert;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['list'],
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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

    public function actionList()
    {
        if (!Yii::$app->user->getIdentity()->is_admin) {
            Alert::add(Yii::t('msg', 'Access denied.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }

        $query = User::find()->orderBy('status', SORT_DESC);

        $users = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('list', [
            'users' => $users
        ]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

}
