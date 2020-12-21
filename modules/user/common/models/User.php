<?php
namespace modules\user\common\models;

use modules\core\db\ActiveRecordWithTimeAndUser;
use modules\user\common\rbac\DbManager;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $role
 *
 * @property string $password write-only password
 */
class User extends ActiveRecordWithTimeAndUser implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVATED = 5;
    const STATUS_ACTIVATED = 10;

    const ROLE_ADMIN = 0;
    const ROLE_ECOLOGY_STAFF = 10;
    const ROLE_GUEST = 100;

    const PASSWORD_NOT_USED = 'password_not_used';

    public function fields()
    {
        return [
            'id',
            'email',
            'status',
            'role',
            'rbac_role_id' => function (User $model) {
                return $model->getRbacRoleId();
            },
        ];
    }

    public static function find()
    {
        return parent::find()->andWhere(['not', ['status' => self::STATUS_DELETED]]);
    }


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
    public function rules()
    {
        return [
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVATED],
            ['role', 'default', 'value' => self::ROLE_GUEST],
            ['status', 'in', 'range' => [self::STATUS_ACTIVATED, self::STATUS_NOT_ACTIVATED, self::STATUS_DELETED]],
            ['role', 'in', 'range' => [
                self::ROLE_ADMIN,
                self::ROLE_ECOLOGY_STAFF,
                self::ROLE_GUEST,
            ]],
        ];
    }

    /**
     * @param int|string $id
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentity($id)
    {
        throw new NotSupportedException('"findIdentity" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVATED]);
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
            'status' => [self::STATUS_NOT_ACTIVATED, self::STATUS_ACTIVATED],
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
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
        throw new NotSupportedException('"getAuthKey" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     * @throws \yii\base\Exception
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
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $session = UserSession::findByToken($token);

        if (null === $session) {
            return null;
        }

        return $session->user;
    }

    public function getRbacRoleId()
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        return $auth->getRoleIdByUser($this->id);
    }
}
