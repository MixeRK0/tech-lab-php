<?php

namespace modules\analysis\common\models;

/**
 * @property integer $id
 * @property integer $result_id
 * @property string $word
 * @property string $morf
 * @property string $synt
 */
class ResultDataUnit extends \modules\core\db\ActiveRecordWithTimeAndUser
{
    const SENTENCE_DELIMITER = 'sentence_delimiter';
    const PUNCTUATION = 'punct';

    const MORF_SYSH = 'sysh';
    const MORF_GLAG = 'glag';
    const MORF_NAR = 'nar';
    const MORF_SOYZ = 'soyz';
    const MORF_PREDLOG = 'predlog';
    const MORF_PRIL = 'pril';
    const MORF_DEEPR = 'deepr';
    const MORF_CHISL = 'chisl';
    const MORF_MEST = 'mest';

    const SYNT_DOUBLE = 'double';
    const SYNT_UNDERLINE = 'underline';
    const SYNT_OBSTOYT = 'obstoyt';
    const SYNT_DOPOL = 'dopol';
    const SYNT_OPREDEL = 'opredel';

    public function rules()
    {
        return [
            [['result_id', 'word', 'morf', 'synt'], 'required'],
            ['result_id', 'integer'],
            [['word', 'morf', 'synt'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'result_data_unit';
    }
}
