<?php

namespace modules\analysis\common\models;

/**
 * @property integer $id
 * @property integer $result_id
 * @property string $str
 * @property string $str_short
 */
class InputText extends \modules\core\db\ActiveRecordWithTimeAndUser
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'input_text';
    }
}
