<?php

namespace modules\user\frontend\controllers;

use modules\user\common\models\User;
use modules\user\frontend\controllers\User\CreateAction;
use modules\user\frontend\controllers\User\DeleteAction;
use modules\user\frontend\controllers\User\UpdateAction;
use yii\rest\ActiveController;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use Yii;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        return array_replace_recursive(
            parent::actions(),
            [
                'index' => [
                    'dataFilter' => [
                        'class' => ActiveDataFilter::class,
                        'searchModel' => function () {
                            $model = new \yii\base\DynamicModel([
                                'query' => null,
                            ]);

                            return
                                $model
                                    ->addRule('query', 'string')
                                ;
                        },
                    ],
                    'prepareDataProvider' => function (IndexAction $action, $filter) {
                        /* @var $modelClass User */
                        $modelClass = $action->modelClass;

                        $query = $modelClass::find();
                        if (!empty($filter)) {
                            $query->andFilterWhere([
                                'LIKE', 'email', $filter['query']
                            ]);
                        }

                        return Yii::createObject([
                            'class' => ActiveDataProvider::class,
                            'query' => $query,
                            'pagination' => [
                                'params' => \Yii::$app->getRequest()->queryParams,
                            ],
                            'sort' => [
                                'params' => \Yii::$app->getRequest()->queryParams,
                            ],
                        ]);
                    }
                ],
                'create' => [
                    'class' => CreateAction::class,
                ],
                'update' => [
                    'class' => UpdateAction::class,
                ],
                'delete' => [
                    'class' => DeleteAction::class,
                ]
            ]
        );
    }
}
