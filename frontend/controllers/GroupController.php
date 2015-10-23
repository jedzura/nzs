<?php

namespace frontend\controllers;

use common\models\Group;
use common\widgets\Alert;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class GroupController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->tag = str_replace('"', '\'', Yii::$app->request->post()['Group']['tag']);
            if (Yii::$app->user->isGuest) {
                Yii::$app->session['groupToSave'] = $model;
                Alert::add(Yii::t('msg', 'Zaloguj się lub zarejestruj, aby dokończyć dodawanie organizacji.'), Alert::TYPE_INFO);
                return $this->redirect(['site/login']);
            }
            if ($model->saveGroup()) {
//            Alert::add(Yii::t('msg', 'Organization successfully added, now it must be accepted by administrator.'));
                Alert::add('Organizacja została pomyślnie dodana, teraz możesz w swoim panelu edytować stronę organizacji.');
                return $this->goHome();
            }
            Alert::add('Coś poszło nie tak, spróbuj ponownie lub zgłoś nam zaistniały problem.', Alert::TYPE_ERROR);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    /**
     * @param string $url
     * @return string
     */
    public function actionIndex($url)
    {
        $model = Group::findOne(['url' => $url]);
        if (!$model) {
            Alert::add('Strona nie została znaleziona', Alert::TYPE_ERROR);
            return $this->goHome();
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionList()
    {
        $groups = Group::find()->orderBy('name')->all();

        return $this->render('list', [
            'groups' => $groups
        ]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
