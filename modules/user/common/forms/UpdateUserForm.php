<?php
namespace modules\user\common\forms;

use modules\user\common\models\User;
use yii\db\Exception;

/**
 * Class UpdateUserForm
 * @package modules\user\common\forms
 */
class UpdateUserForm extends User
{
    public $id;
    public $email;
    public $role;
    public $rbac_role_id;
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['id'], 'required'],
                ['rbac_role_id', 'integer'],
                [['password', 'password_repeat'], 'string']
            ]
        );
    }

    /**
     * @return User
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function findAndFillUser()
    {
        $user = User::findOne($this->id);

        return $this->fillAttributesForExistUser($user);
    }

    /**
     * @param User $user
     * @return User
     * @throws Exception
     * @throws \yii\base\Exception
     */
    private function fillAttributesForExistUser(User $user)
    {
        $user->email = $this->email;
        $user->role = $this->role;
        if ($this->password || $this->password_repeat) {
            if ($this->password === $this->password_repeat) {
                $user->setPassword($this->password);
            } else {
                throw new Exception('Passwords don\'t match!');
            }
        }

        return $user;
    }
}
