<?php

namespace modules\user\frontend\controllers\RoleAssign;

use modules\user\frontend\controllers\RoleAssignForm;
use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

class CreateAction extends Action
{
    public function run()
    {
        $form = new RoleAssignForm();

        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->assign()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        } elseif (!$form->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $form;
    }
}
