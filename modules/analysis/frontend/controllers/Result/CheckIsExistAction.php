<?php

namespace modules\analysis\frontend\controllers\Result;

use modules\analysis\common\models\InputText;
use Yii;
use yii\rest\Action;

class CheckIsExistAction extends Action
{
    public function run()
    {
        $shortText = substr(Yii::$app->getRequest()->getBodyParam('text'), 0, 100);
        $result = InputText::find()->where(['str_short' => $shortText])->select('result_id')->asArray()->one();

        return $result ? floatval($result['result_id']) : null;
    }
}
