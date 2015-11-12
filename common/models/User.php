<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $is_admin
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $facebook
 * @property string $twitter
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $newPassword
 * @property string $passwordConfirm
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public
        $newPassword,
        $passwordConfirm;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'firstname', 'lastname'], 'required', 'on' => 'update'],
            [['is_admin', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['email'], 'filter', 'filter' => 'trim'],
            [['email'], 'email'],
            [['firstname', 'lastname', 'email', 'facebook', 'twitter'], 'string', 'max' => 64],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['newPassword'], 'string', 'min' => 6],
            [['passwordConfirm'], 'required', 'when' => function($model)
            {
                return !empty($model->newPassword);
            }, 'whenClient' => "function (attribute, value)
            {
                return $('#user-newpassword').val() !== '';
            }"],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['firstname', 'lastname', 'email', 'facebook', 'twitter', 'newPassword', 'passwordConfirm'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lbl', 'ID'),
            'is_admin' => Yii::t('lbl', 'Is Admin'),
            'username' => Yii::t('lbl', 'Username'),
            'password_hash' => Yii::t('lbl', 'Password hash'),
            'password_reset_token' => Yii::t('lbl', 'Password reset token'),
            'firstname' => Yii::t('lbl', 'First name'),
            'lastname' => Yii::t('lbl', 'Last name'),
            'email' => Yii::t('lbl', 'Email'),
            'facebook' => Yii::t('lbl', 'Facebook'),
            'twitter' => Yii::t('lbl', 'Twitter'),
            'auth_key' => Yii::t('lbl', 'Authorization key'),
            'status' => Yii::t('lbl', 'Status'),
            'created_at' => Yii::t('lbl', 'Create date'),
            'updated_at' => Yii::t('lbl', 'Update date'),
            'newPassword' => Yii::t('lbl', 'New password'),
            'passwordConfirm' => Yii::t('lbl', 'Password confirm'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHisGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_to_group', ['user_id' => 'id'])->where(['group_admin' => $this->id]);
    }
}
