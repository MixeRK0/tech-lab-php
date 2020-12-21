<?php


namespace modules\user\frontend\controllers;

use modules\core\rbac\Permission;
use modules\user\frontend\controllers\Permission\IndexAction;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;

class PermissionController extends ActiveController
{
    public $modelClass = Permission::class;

    public function actions()
    {
        return [
            'index' => IndexAction::class,
            'options' => OptionsAction::class
        ];
    }
}
