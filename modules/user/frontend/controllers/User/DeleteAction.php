<?php

namespace modules\user\frontend\controllers\User;

use modules\user\common\models\User;
use yii\rest\Action;
use yii;
use yii\web\ServerErrorHttpException;

class DeleteAction extends Action
{
    /**
     * @param $id
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        $user = User::findOne($id);
        $user->status = User::STATUS_DELETED;

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $user);
        }

        if ($user->save()) {
            Yii::$app->getResponse()->setStatusCode(204);
        } else {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
    }
}
