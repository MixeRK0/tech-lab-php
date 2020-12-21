<?php
namespace modules\user\common\forms;

use modules\user\common\models\User;

/**
 * Class CreateUserForm
 * @package modules\user\common\forms
 */
class CreateUserForm extends User
{
    public $email;
    public $role;
    public $rbac_role_id;
    public $is_create_by_admin;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['email', 'role'], 'required'],
                ['password', 'string'],
                ['is_create_by_admin', 'boolean'],
                ['rbac_role_id', 'integer']
            ]
        );
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    public function createAndFillUser()
    {
        $user = new User();

        return $this->fillAttributesForNewUser($user);
    }

    /**
     * @param User $user
     * @return User
     * @throws \yii\base\Exception
     */
    private function fillAttributesForNewUser(User $user)
    {
        $user->email = $this->email;
        $user->role = $this->role;
        if ($this->is_create_by_admin) {
            $user->status = User::STATUS_ACTIVATED;
            $user->setPassword($this->password);
        } else {
            $user->status = User::STATUS_NOT_ACTIVATED;
            $user->generatePasswordResetToken();
            $user->setPassword(\Yii::$app->security->generateRandomString(8));
        }

        return $user;
    }
}
