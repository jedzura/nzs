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
        $group = new Group();

        if ($group->load(Yii::$app->request->post()))
        {
            $group->tag = str_replace('"', '\'', Yii::$app->request->post()['Group']['tag']);
            if (Yii::$app->user->isGuest) {
                Yii::$app->session['groupToSave'] = $group;
                Alert::add(Yii::t('msg', 'Zaloguj się lub zarejestruj, aby dokończyć dodawanie organizacji.'), Alert::TYPE_INFO);
                return $this->redirect(['site/login']);
            }
            if ($group->saveGroup()) {
//            Alert::add(Yii::t('msg', 'Organization successfully added, now it must be accepted by administrator.'));
                Alert::add('msg', 'Operation completed successfully. Now you can edit your organization details in user panel.');
                return $this->goHome();
            }
            Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
        }

        return $this->render('create', [
            'model' => $group
        ]);
    }

    public function actionDelete($id)
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->getIdentity();
        /** @var \common\models\Group $group */
        $group = Group::findOne($id);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Organization not found.'), Alert::TYPE_ERROR);
            return $this->redirect(['group/user-group-list']);
        }
        $canEdit = UserToGroup::findOne(['group_id' => $id, 'user_id' => $user->id, 'can_edit' => 1]);
        if (!$canEdit && !$user->is_admin) {
            Alert::add(Yii::t('msg', 'You have no permission for this action.'), Alert::TYPE_ERROR);
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
        $group = Group::findOne(['url' => $url]);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Organization not found.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }

        return $this->render('index', [
            'model' => $group
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
        $groupSearch = new GroupSearch();

        $results = null;
        if (Yii::$app->request->post()) {
            $groupSearch->name = Yii::$app->request->post('GroupSearch')['name'];
            $groupSearch->city_id = Yii::$app->request->post('GroupSearch')['city_id'];
            $groupSearch->university_id = Yii::$app->request->post('GroupSearch')['university_id'];
            $groupSearch->tag_id = Yii::$app->request->post('GroupSearch')['tag_id'];
            $results = $groupSearch->search();
        }

        return $this->render('search', [
            'model' => $groupSearch,
            'results' => $results,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = Yii::$app->user->getIdentity();
        $group = Group::find()->leftJoin(UserToGroup::tableName() . ' AS utg', ['user_id' => $user->id])->where(['group.id' => $id, 'can_edit' => 1])->one();
        if (!$group) {
            Alert::add(Yii::t('msg', 'You have no permission for this action.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }

        if ($group->load(Yii::$app->request->post())) {
            $group->tag = str_replace('"', '\'', Yii::$app->request->post()['Group']['tag']);
            $group->logo = UploadedFile::getInstance($group, 'logo');
            if ($group->updateGroup()) {
                Alert::add(Yii::t('msg', 'Operation completed successfully.'));
                return $this->redirect(['group/user-group-list']);
            } else {
                Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
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

    public function actionJoin($url)
    {
        $group = Group::findOne(['url' => $url]);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Organization not found.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }
        $user = Yii::$app->user->getIdentity();
        $userToGroup = UserToGroup::findOne(['group_id' => $group->id, 'user_id' => $user->id]);
        if ($userToGroup) {
            if (!$userToGroup->status) {
                Alert::add(Yii::t('msg', 'Your request for joining this organization is waiting for organization admin to accept.'), Alert::TYPE_INFO);
            } else {
                Alert::add(Yii::t('msg', 'Your are already a member of this organization.'), Alert::TYPE_INFO);
            }
        } else {
            $userToGroup = new UserToGroup();
            $userToGroup->user_id = $user->id;
            $userToGroup->group_id = $group->id;
            $userToGroup->group_admin = 0;
            $userToGroup->can_edit = 0;
            if ($userToGroup->save()) {
                Alert::add(Yii::t('msg', 'Your request for joining this organization is waiting for organization admin to accept.'));
            } else {
                Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
            }
        }

        return $this->redirect(['group/index', 'url' => $url]);
    }

    public function actionLeave($url)
    {
        $group = Group::findOne(['url' => $url]);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Organization not found.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }
        $user = Yii::$app->user->getIdentity();
        $userToGroup = UserToGroup::findOne(['group_id' => $group->id, 'user_id' => $user->id]);
        if (!$userToGroup) {
            Alert::add(Yii::t('msg', 'Your are not a member of this organization.'), Alert::TYPE_INFO);
        } else {
            if ($userToGroup->delete()) {
                Alert::add(Yii::t('msg', 'Operation completed successfully.'));
            } else {
                Alert::add(Yii::t('msg', 'Something goes wrong, try again or contact us.'), Alert::TYPE_ERROR);
            }
        }

        return $this->redirect(['group/index', 'url' => $url]);
    }

    public function actionContact($url)
    {
        $group = Group::findOne(['url' => $url]);
        if (!$group) {
            Alert::add(Yii::t('msg', 'Organization not found.'), Alert::TYPE_ERROR);
            return $this->goHome();
        }
        if (!$group->email) {
            Alert::add(Yii::t('msg', 'This organization does not have any contact email.'), Alert::TYPE_INFO);
            
        }
    }
}
