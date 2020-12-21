<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'prizma-server-front-office',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [],
    'modules' => [
        'user' => \modules\user\frontend\user::class,
        'event' => \modules\events\frontend\events::class,
        'analysis' => \modules\analysis\frontend\analysis::class
    ],
    'components' => [
        'user' => [
            'identityClass' => \modules\user\common\models\User::class,
            'enableSession' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'tokens' => [
                        '{id}' => '<id:\\d[\\d,]*>',
                    ],
                    'controller' => [
                        'events' => 'event/event',
                        'users' => 'user/user',
                        'user/roles' => 'user/role',
                        'user/role-assign' => 'user/role-assign',
                        'user/permissions' => 'user/permission',
                        'results' => 'analysis/result',
                        'result-units' => 'analysis/result-data-unit',
                    ],
                    'patterns' => [
                        'PUT {id}' => 'update',
                        'DELETE {id}' => 'delete',
                        'GET,HEAD {id}' => 'view',
                        'POST' => 'create',
                        'POST {id}' => 'copy',
                        'GET,HEAD' => 'index',
                        '{id}' => 'options',
                        '' => 'options',
                    ],
                    'extraPatterns' => [
                        'PUT,OPTIONS check-is-exist' => 'check-is-exist',
                        'GET,OPTIONS filtered-result' => 'filtered-result',
                    ],
                ],
            ],
        ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'class' => \yii\web\Response::class,
            'format' => 'json',
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => \yii\web\JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
            'on ' . \yii\web\Response::EVENT_AFTER_SEND => function () {
                if (Yii::$app->getResponse()->getStatusCode() >= 400) {
                    \modules\core\base\ErrorLogger::log();
                }
            },
        ],
    ],
    'params' => $params,
];
