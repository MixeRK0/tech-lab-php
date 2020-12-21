<?php

namespace modules\user\frontend\controllers\Role;

use modules\user\common\rbac\DbManager;
use Yii;
use yii\base\Action;

class ViewAction extends Action
{
    public function run($id)
    {
        /** @var DbManager $auth */
        $auth = Yii::$app->authManager;
        $role = $auth->getRoleById($id);

        return $role;
    }
}
