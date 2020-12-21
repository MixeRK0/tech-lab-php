<?php

namespace modules\user\frontend\controllers;

use modules\user\frontend\controllers\Role\CreateAction;
use modules\user\frontend\controllers\Role\DeleteAction;
use modules\user\frontend\controllers\Role\IndexAction;
use modules\user\frontend\controllers\Role\UpdateAction;
use modules\user\frontend\controllers\Role\ViewAction;
use yii\rest\ActiveController;

class RoleController extends ActiveController
{
    public $modelClass = RoleForm::class;

    public function actions()
    {
        return array_replace(
            parent::actions(),
            [
                'index' => IndexAction::class,
                'view' => ViewAction::class,
                'create' => CreateAction::class,
                'update' => UpdateAction::class,
                'delete' => DeleteAction::class
            ]
        );
    }
}
