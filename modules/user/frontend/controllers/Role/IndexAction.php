<?php

namespace modules\user\frontend\controllers\Role;

use Yii;
use yii\base\Action;
use yii\data\ArrayDataProvider;

class IndexAction extends Action
{
    public function run()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $auth = Yii::$app->authManager;

        return Yii::createObject([
            'class' => ArrayDataProvider::class,
            'allModels' => $auth->getRoles(),
            'pagination' => [
                'params' => $requestParams,
                'pageSizeLimit' => [1, PHP_INT_MAX],
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}
