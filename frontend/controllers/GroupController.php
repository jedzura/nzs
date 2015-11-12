<?php

namespace frontend\controllers;

use common\models\Group;
use common\models\UserToGroup;
use frontend\models\GroupSearch;
use common\widgets\Alert;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

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
            Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->getIdentity();
        /** @var \common\models\Group $group */
        $group = Group::findOne($id);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Not found.'), Alert::TYPE_ERROR);
            return $this->redirect(['group/user-group-list']);
        }
        $canEdit = UserToGroup::find()->where(['group_id' => $id, 'user_id' => $user->id, 'can_edit' => 1])->one();
        if (!$canEdit && !$user->is_admin) {
            Alert::add('Nie masz uprawnień by usunąć tą organizację.', Alert::TYPE_ERROR);
            return $this->redirect(['group/user-group-list']);
        }

        if ($group->delete()) {
            Alert::add(Yii::t('msg', 'Operation completed successfully.'));
        } else {
            Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
        }

        return $this->redirect(['group/user-group-list']);
    }

    /**
     * @param string $url
     * @return string
     */
    public function actionIndex($url)
    {
        $model = Group::findOne(['url' => $url]);
        if (!$model) {
            Alert::add(Yii::t('msg', 'Page not found.'), Alert::TYPE_ERROR);
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

    public function actionSearch()
    {
        $model = new GroupSearch();

        $results = null;
        if (Yii::$app->request->post()) {
            $model->name = Yii::$app->request->post('GroupSearch')['name'];
            $model->city_id = Yii::$app->request->post('GroupSearch')['city_id'];
            $model->university_id = Yii::$app->request->post('GroupSearch')['university_id'];
            $model->tag_id = Yii::$app->request->post('GroupSearch')['tag_id'];
            $results = $model->search();
        }

        return $this->render('search', [
            'model' => $model,
            'results' => $results,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = Yii::$app->user->getIdentity();
        $group = Group::find()->leftJoin(UserToGroup::tableName() . ' AS utg', ['user_id' => $user->id])->where(['group.id' => $id, 'can_edit' => 1])->one();
        if (!$group) {
            Alert::add('Nie masz uprawnień do edytowania tej organizacji.', Alert::TYPE_ERROR);
            return $this->goHome();
        }

        if ($group->load(Yii::$app->request->post())) {
            $group->tag = str_replace('"', '\'', Yii::$app->request->post()['Group']['tag']);
            $group->logo = UploadedFile::getInstance($group, 'logo');
            if ($group->updateGroup()) {
                Alert::add('Zmiany pomyślnie zapisane.');
                return $this->redirect(['group/user-group-list']);
            }
        }

        if ($group->tags) {
            $tagsArray = [];
            foreach ($group->tags as $tag) {
                $tagsArray[] = $tag->name;
            }
            $group->tag = implode('","', $tagsArray);
            $group->tag = '["' . $group->tag . '"]';
        }

        return $this->render('update', [
            'model' => $group
        ]);
    }

    public function actionUserGroupList()
    {
        $user = Yii::$app->user->getIdentity();
        $query = Group::find()->leftJoin(UserToGroup::tableName() . ' AS utg', 'utg.group_id = group.id')
            ->where(['utg.user_id' => $user->id]);
        if ($user->is_admin) {
            $queryOwner = Group::find();
        } else {
            $queryOwner = Group::find()->leftJoin(UserToGroup::tableName() . ' AS utg', 'utg.group_id = group.id')
                ->where(['utg.user_id' => $user->id, 'utg.can_edit' => 1]);
        }
        $userGroups = new ActiveDataProvider([
            'query' => $query
        ]);

        $ownerGroups = new ActiveDataProvider([
            'query' => $queryOwner
        ]);

        return $this->render('user-group-list', [
            'userGroups' => $userGroups,
            'ownerGroups' => $ownerGroups
        ]);
    }

}
