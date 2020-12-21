<?php

namespace modules\analysis\frontend\controllers\Result;

use modules\analysis\common\models\InputText;
use modules\analysis\common\models\ResultDataUnit;
use Yii;
use yii\rest\Action;

class FilteredResultAction extends Action
{
    public function run(int $id, string $word, string $morf, string $synt)
    {
        $result = [];

        $targetResultDataUnits = ResultDataUnit::find()
            ->where(['result_id' => $id])
            ->andWhere(['word' => $word])
            ->andWhere(['morf' => $morf])
            ->andWhere(['synt' => $synt])
            ->all();

        foreach ($targetResultDataUnits as $unit) {
            /** @var ResultDataUnit $startUnit */
            $startUnitId = ResultDataUnit::find()
                ->where(['result_id' => $id])
                ->andWhere(['morf' => 'sentence_delimiter'])
                ->andWhere(['<', 'id', $unit->id])
                ->orderBy(['id' => SORT_DESC])
                ->select('id')
                ->one()['id'];

            if ($startUnitId) {
                $startUnitId += 1;
            } else {
                $startUnitId = ResultDataUnit::find()
                    ->where(['result_id' => $id])
                    ->orderBy(['id' => SORT_ASC])
                    ->select('id')
                    ->one()['id'];
            }

            /** @var ResultDataUnit $endUnit */
            $endUnitId = ResultDataUnit::find()
                ->where(['result_id' => $id])
                ->andWhere(['morf' => 'sentence_delimiter'])
                ->andWhere(['>', 'id', $unit->id])
                ->orderBy(['id' => SORT_ASC])
                ->select('id')
                ->one()['id'];

            $result = array_merge(
                $result,
                ResultDataUnit::find()
                    ->where(['>=', 'id', $startUnitId])
                    ->andWhere(['<=', 'id', $endUnitId])
                    ->all()
            );
        }

        return $result;
    }
}
