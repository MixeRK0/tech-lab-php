<?php

namespace modules\analysis\frontend\controllers;

use modules\analysis\common\models\Result;
use modules\analysis\frontend\controllers\Result\CheckIsExistAction;
use modules\analysis\frontend\controllers\Result\FilteredResultAction;
use yii\rest\ActiveController;

class ResultController extends ActiveController
{
    public $modelClass = Result::class;

    public function actions()
    {
        return array_merge_recursive(
            parent::actions(),
            [
                'index' => [
                    'dataFilter' => [
                        'class' => 'yii\data\ActiveDataFilter',
                        'searchModel' => function () {

                            $model = new \yii\base\DynamicModel([
                                'user_id' => null,
                            ]);

                            return $model->addRule('user_id', 'integer');
                        },
                    ],
                ],
                'check-is-exist' => [
                    'class' => CheckIsExistAction::class,
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                ],
                'filtered-result' => [
                    'class' => FilteredResultAction::class,
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                ],
            ]
        );
    }
}
