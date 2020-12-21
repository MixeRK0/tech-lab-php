<?php


namespace modules\user\frontend\controllers;

use modules\user\frontend\controllers\RoleAssign\CreateAction;
use yii\rest\ActiveController;
use yii\rest\OptionsAction;

class RoleAssignController extends ActiveController
{
    public $modelClass = RoleAssignForm::class;

    public function actions()
    {
        return [
            'create' => CreateAction::class,
            'options' => OptionsAction::class
        ];
    }
}
