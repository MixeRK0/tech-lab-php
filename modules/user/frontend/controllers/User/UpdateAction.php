<?php

namespace modules\user\frontend\controllers\User;

use modules\user\common\forms\UpdateUserForm;
use modules\user\common\models\User;
use modules\user\frontend\controllers\RoleAssignForm;
use Yii;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class UpdateAction extends Action
{
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario;

    /**
     * @return UpdateUserForm|User
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $form = new UpdateUserForm([
            'scenario' => $this->scenario,
        ]);
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($form->validate()) {
            $user = $form->findAndFillUser();
            if ($user->getRbacRoleId() !== $form->rbac_role_id) {
                $oldRoleId = $user->getRbacRoleId();
                $newRoleId = $form->rbac_role_id;

                $oldAssignment = new RoleAssignForm([
                    'role_id' => $user->getRbacRoleId(),
                    'user_id' => $user->id
                ]);
                $oldAssignment->revoke();

                $newAssignment = new RoleAssignForm([
                    'role_id' => $form->rbac_role_id,
                    'user_id' => $user->id
                ]);
                $newAssignment->assign();

                if ($newAssignment->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to create the assignment!');
                }
            }

            if ($this->checkAccess) {
                call_user_func($this->checkAccess, $this->id, $user);
            }

            $user->scenario = $this->scenario;
            if ($user->save()) {
                return $user;
            } else if (!$form->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        }

        return $form;
    }

}
