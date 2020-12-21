<?php

namespace modules\user\frontend\controllers;

use modules\user\common\rbac\DbManager;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    public $name;

    public $description = '';

    public $permission_names;

    public function rules()
    {
        $permissions = \Yii::$app->authManager->getPermissions();

        return [
            [['name', 'permission_names'], 'required'],
            ['name', 'string', 'max' => 127],
            ['description', 'string', 'max' => 127],
            [
                'permission_names',
                'in',
                'range' => ArrayHelper::getColumn($permissions, 'name', false),
                'allowArray' => true
            ]
        ];
    }

    /**
     * @return Role
     * @throws Exception
     */
    public function CreateAndSaveRole()
    {
        $auth = \Yii::$app->authManager;

        $role = $auth->createRole($this->name);

        $role->description = $this->description;

        $tran = Yii::$app->db->beginTransaction();
        if ($auth->add($role) === false) {
            $tran->rollBack();

            $this->addError('name', 'Invalid name');

            throw new Exception("Error on add role '$role->name'");
        }

        try {
            $this->addPermissions($auth, $role);
        } catch (Throwable $exception) {
            $tran->rollBack();
        }

        $tran->commit();

        return $auth->getRole($this->name);
    }

    /**
     * @param Role $role
     * @param string $oldRoleName
     * @return Role
     * @throws \Exception
     */
    public function UpdateAndSaveRole(Role $role, string $oldRoleName)
    {
        $auth = \Yii::$app->authManager;
        $tran = \Yii::$app->db->beginTransaction();
        try {
            if ($auth->update($oldRoleName, $role) === false) {
                throw new Exception("Error on remove role '$role->name'");
            }

            $oldPermissionsNames = [];
            foreach ($auth->getPermissionsByRole($role->name) as $oldPermissionName => $oldPermission) {
                array_push($oldPermissionsNames, $oldPermissionName);
            }

            $this->deletePermissions($auth, $role);
            $this->addPermissions($auth, $role);

            $tran->commit();

            $newPermissionsNames = [];
            foreach ($auth->getPermissionsByRole($role->name) as $newPermissionName => $newPermission) {
                array_push($newPermissionsNames, $newPermissionName);
            }

        } catch (Throwable $exception) {
            $tran->rollBack();

            throw $exception;
        }

        return $role;
    }

    /**
     * @param DbManager $auth
     * @param Role $role
     * @throws Exception
     */
    private function deletePermissions($auth, $role)
    {
        $permissions = $auth->getPermissionsByRole($role->name);

        foreach ($permissions as $permission) {
            $auth->removeChild($role, $permission);
        }
    }

    private function addPermissions($auth, $role)
    {
        if (is_array($this->permission_names)) {
            foreach ($this->permission_names as $permissionName) {
                $permission = $auth->getPermission($permissionName);

                if (is_null($permission)) {
                    continue;
                }

                if ($auth->addChild($role, $permission) === false) {
                    $this->addError('permissions', 'Invalid permission');

                    throw new Exception("Error on add child '$permission->name'");
                }
            }
        }
    }
}
