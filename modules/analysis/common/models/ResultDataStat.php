<?php

namespace modules\analysis\common\models;

use modules\analysis\common\services\AnalyserApiService;

/**
 * @property integer $id
 * @property integer $result_id
 * @property integer $word
 * @property integer $sysh
 * @property integer $glag
 * @property integer $nar
 * @property integer $soyz
 * @property integer $predlog
 * @property integer $pril
 * @property integer $prich
 * @property integer $deepr
 * @property integer $chisl
 * @property integer $mest
 * @property integer $morf_undefined
 * @property integer $double
 * @property integer $underline
 * @property integer $dopol
 * @property integer $obstoyt
 * @property integer $opredel
 * @property integer $synt_undefined
 * @property integer $not_set
 * @property integer $summary
 */
class ResultDataStat extends \modules\core\db\ActiveRecordWithTimeAndUser
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'result_data_stat';
    }
}
