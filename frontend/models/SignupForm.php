<?php
namespace frontend\models;

use common\models\User;
use common\widgets\Alert;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public
        $username,
        $email,
        $firstname,
        $lastname,
        $password,
        $passwordConfirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('msg', 'The username has already been taken.')],
            ['username', 'string', 'min' => 3, 'max' => 32],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 64],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ten adres email jest już w użyciu.'],

            [['firstname', 'lastname'], 'filter', 'filter' => 'trim'],
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'min' => 2, 'max' => 64],

            [['password'], 'required'],
            [['password'], 'string', 'min' => 6],

            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('lbl', 'Username'),
            'email' => Yii::t('lbl', 'Email'),
            'firstname' => Yii::t('lbl', 'First name'),
            'lastname' => Yii::t('lbl', 'Last name'),
            'password' => Yii::t('lbl', 'Password'),
            'passwordConfirm' => Yii::t('lbl', 'Password confirm'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->is_admin = 0;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
