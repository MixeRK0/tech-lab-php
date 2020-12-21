<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'log'
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => getenv('CACHE_DIR') ?: '@common/runtime/cache'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Hide index.php
            'showScriptName' => false,
            // Use pretty URLs
            'enablePrettyUrl' => true,
            'rules' => [
            ],
        ],
        'authManager' => [
            'class' => \modules\user\common\rbac\DbManager::class,
        ],
        'i18n' => [
            'translations' => [
                'file' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
    ],
];
