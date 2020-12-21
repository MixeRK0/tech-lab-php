<?php

namespace modules\user\backend\controllers;

use modules\user\common\models\User;
use yii\rest\ActiveController;

class DefaultController extends ActiveController
{
    public $modelClass = User::class;
}