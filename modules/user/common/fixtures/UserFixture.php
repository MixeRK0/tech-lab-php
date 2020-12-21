<?php
namespace modules\user\common\fixtures;

use modules\user\common\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}