<?php

namespace modules\user\frontend\controllers;

use modules\user\common\models\User;
use modules\user\common\rbac\DbManager;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleAssignForm extends Model
{
    public $role_id;

    public $user_id;

    public function rules()
    {
        $roles = \Yii::$app->authManager->getRoles();

        return [
            [['role_id', 'user_id'], 'required'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['role_id', 'in', 'range' => ArrayHelper::getColumn($roles, 'id', false), 'allowArray' => true]
        ];
    }

    public function assign()
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $role = $auth->getRoleById($this->role_id);

        return $auth->assign($role, $this->user_id);
    }

    public function revoke()
    {
        /** @var DbManager $auth */
        $auth = \Yii::$app->authManager;

        $role = $auth->getRoleById($this->role_id);

        if (!$role) {
            return false;
        }

        return $auth->revoke($role, $this->user_id);
    }
}
