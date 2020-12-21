<?php

namespace modules\user\frontend\controllers\Role;

use modules\user\common\rbac\DbManager;
use modules\user\frontend\controllers\RoleForm;
use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

class UpdateAction extends Action
{
    public function run($id)
    {
        /** @var DbManager $auth */
        $auth = Yii::$app->authManager;
        $role = $auth->getRoleById($id);

        $form = new RoleForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');

        $oldRoleName = $role->name;
        $role->name = $form->name;
        $role->description = $form->description;

        $role = $form->UpdateAndSaveRole($role, $oldRoleName);
        if ($role === false && !$form->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $role;
    }
}
