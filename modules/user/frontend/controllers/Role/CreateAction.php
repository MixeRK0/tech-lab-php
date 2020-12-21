<?php

namespace modules\user\frontend\controllers\Role;

use modules\user\frontend\controllers\RoleForm;
use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

class CreateAction extends Action
{
    public function run()
    {
        $form = new RoleForm();

        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        $role = $form->CreateAndSaveRole();

        if ($role !== null) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$form->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $role;
    }
}
