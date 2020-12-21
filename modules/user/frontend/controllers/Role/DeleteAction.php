<?php

namespace modules\user\frontend\controllers\Role;

use modules\user\common\rbac\DbManager;
use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

class DeleteAction extends Action
{
    public function run($id)
    {
        /** @var DbManager $auth */
        $auth = Yii::$app->authManager;
        $role = $auth->getRoleById($id);

        if ($auth->remove($role) === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
