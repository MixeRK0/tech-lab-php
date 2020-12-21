<?php
namespace modules\user\common\models;

use yii\base\Model;

class UserWithSession extends Model
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $accessToken;

    public function __construct(User $user, string $accessToken)
    {
        parent::__construct([]);

        $this->user = $user;
        $this->accessToken = $accessToken;
    }

    public function fields()
    {
        return [
            'id' => function (UserWithSession $model) {
                return $model->getUser()->id;
            },
            'email' => function (UserWithSession $model) {
                return $model->getUser()->email;
            },
            'status' => function (UserWithSession $model) {
                return $model->getUser()->status;
            },
            'access_token' => function (UserWithSession $model) {
                return $model->getAccessToken();
            },
            'role' => function (UserWithSession $model) {
                return $model->getUser()->role;
            },
            'rbac_role_id' => function (UserWithSession $model) {
                return $model->getUser()->getRbacRoleId();
            },
        ];
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
