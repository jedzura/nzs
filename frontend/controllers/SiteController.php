<?php
namespace frontend\controllers;

use common\models\LoginForm;
use common\models\University;
use common\widgets\Alert;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['group/create']);
//        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            Alert::add(Yii::t('msg', 'You are already logged in.'), Alert::TYPE_INFO);
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Alert::add(Yii::t('msg', 'You are logged in.'));
            if (isset(Yii::$app->session['groupToSave'])) {
                $group = Yii::$app->session['groupToSave'];
                if ($group->saveGroup()) {
                    unset(Yii::$app->session['groupToSave']);
                    Alert::add('Organizacja została pomyślnie dodana, teraz możesz w swoim panelu edytować stronę organizacji.');
                } else {
                    Alert::add('Coś poszło nie tak, spróbuj ponownie lub zgłoś nam zaistniały problem.', Alert::TYPE_ERROR);
                }
            }
            return $this->redirect(['panel/index']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Alert::add(Yii::t('msg', 'You have been logged out.'));

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Alert::add('Wiadomość została wysłana, odpowiemy na nią tak szybko jak to będzie możliwe.');
            } else {
                Alert::add('Wystąpił błąd, spróbuj ponownie.', Alert::TYPE_ERROR);
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            Alert::add(Yii::t('msg', 'You are already logged in.'), Alert::TYPE_INFO);
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Alert::add(Yii::t('msg', 'Registration completed successfully.'));
                if (Yii::$app->getUser()->login($user)) {
                    if (isset(Yii::$app->session['groupToSave'])) {
                        $group = Yii::$app->session['groupToSave'];
                        if ($group->saveGroup()) {
                            unset(Yii::$app->session['groupToSave']);
                            Alert::add('Organizacja została pomyślnie dodana, teraz możesz w swoim panelu edytować stronę organizacji.');
                        } else {
                            Alert::add('Coś poszło nie tak, spróbuj ponownie lub zgłoś nam zaistniały problem.', Alert::TYPE_ERROR);
                        }
                    }
                    return $this->redirect(['panel/index']);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Alert::add(Yii::t('msg', 'Check your email for further instructions.'));
                return $this->goHome();
            } else {
                Alert::add(Yii::t('msg', 'Sorry, we are unable to reset password for email provided.'), Alert::TYPE_ERROR);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Alert::add(Yii::t('msg', 'New password was saved. Now you can log in.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public function actionGetUniversities()
    {
        $city_id = Yii::$app->request->post('city_id');
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        return json_encode(ArrayHelper::map(University::find()->where(['city_id' => $city_id])->orderBy('name')->all(), 'id', 'name'));
    }
}
