<?php

namespace modules\user\frontend\controllers\User;

use modules\user\common\forms\CreateUserForm;
use modules\user\frontend\controllers\RoleAssignForm;
use Yii;
use yii\rbac\Assignment;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class CreateAction extends Action
{
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';

    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario;

    /**
     * @return CreateUserForm|\modules\user\common\models\User
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $form = new CreateUserForm([
            'scenario' => $this->scenario,
        ]);
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($form->validate()) {
            $user = $form->createAndFillUser();
            if ($user->save()) {
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(201);

                $assignment = new RoleAssignForm();
                $assignment->user_id = $user->id;
                $assignment->role_id = $form->rbac_role_id;
                $assignment->assign();

                if ($assignment->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to create the assignment!');
                }

                return $user;
            } else if (!$user->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }

            return $user;
        }

        return $form;
    }
}
