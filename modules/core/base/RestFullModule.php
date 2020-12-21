<?php

namespace modules\core\base;

use Yii;
use yii\base\Module;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class RestFullModule extends Module
{
    public function init()
    {
        parent::init();

        Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'corsFilter' => [
                    'class' => Cors::class,
                    'cors' => [
                        'Origin' => Yii::$app->params['allowed-client-hosts'],
                        'Access-Control-Request-Method' => [
                            'GET',
                            'POST',
                            'PUT',
                            'PATCH',
                            'DELETE',
                            'HEAD',
                            'OPTIONS',
                            'COPY'
                        ],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Allow-Credentials' => true,
                        'Access-Control-Max-Age' => 86400,
                        'Access-Control-Expose-Headers' => [
                            'X-Pagination-Current-Page',
                            'X-Pagination-Per-Page',
                            'X-Pagination-Page-Count',
                            'X-Pagination-Total-Count'
                        ],
                    ],
                ],
                'authenticator' => [
                    'class' => HttpBearerAuth::class,
                    'except' => ['*/options']
                ],
            ]
        );
    }
}
