<?php

namespace modules\analysis\common\models;

use modules\analysis\common\services\AnalyserApiService;
use Yii;

/**
 * @property integer $id
 * @property integer $user_id
 *
 * @property ResultDataUnit[] $data
 * @property ResultDataStat[] $stat
 * @property string $inputText
 *
 */
class Result extends \modules\core\db\ActiveRecordWithTimeAndUser
{
    public $input_text;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['user_id', 'integer'],
                ['input_text', 'required']
            ]
        );
    }

    public function extraFields()
    {
        return array_merge(
            parent::extraFields(),
            [
                'data' => function (Result $model) {
                    return $model->data;
                },
                'stat' => function (Result $model) {
                    return $model->stat;
                },
            ]
        );
    }

    public function fields()
    {
        return array_merge(
            parent::fields(),
            [
                'input_text' => function (Result $model) {
                    return $model->getInputText()->one()->str;
                }
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'result';
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInputText()
    {
        return $this->hasOne(InputText::class, ['result_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasMany(ResultDataUnit::class, ['result_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStat()
    {
        return $this->hasMany(ResultDataStat::class, ['result_id' => 'id'])->orderBy(['summary' => SORT_DESC]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $text = new InputText();
        $text->result_id = $this->id;
        $text->str = $this->input_text;
//        $text->str_short = substr($this->input_text, 0, 100);
        $text->save();

        $apiService = new AnalyserApiService();

        $morfAnalyseData = $apiService->createTextAnalyseMorf($this->input_text);
        $stat = [];
        foreach ($morfAnalyseData as $wordData) {
            $wordData = (array)$wordData;
            $resultUnit = new ResultDataUnit();
            $resultUnit->word = $wordData['item1'];
            $resultUnit->morf = self::ResolveMorf($wordData['item2']);
            $resultUnit->synt = self::ResolveSynt($wordData['item3']);
            $str = $wordData['item1'];
            if ($str === '.' || $str === '?' || $str === '!' || $str === '!!!' || $str === '???' || $str === '...') {
                $resultUnit->morf = ResultDataUnit::SENTENCE_DELIMITER;
                $resultUnit->synt = ResultDataUnit::SENTENCE_DELIMITER;
            }
            $resultUnit->result_id = $this->id;

            $resultUnit->save();

            if ($resultUnit->morf !== 'sentence_delimiter' && $resultUnit->morf !== 'punct') {
                if (array_key_exists($resultUnit->word, $stat) === false) {
                    $stat[$resultUnit->word] = [
                        ResultDataUnit::MORF_SYSH => 0,
                        ResultDataUnit::MORF_GLAG => 0,
                        ResultDataUnit::MORF_NAR => 0,
                        ResultDataUnit::MORF_SOYZ => 0,
                        ResultDataUnit::MORF_PREDLOG => 0,
                        ResultDataUnit::MORF_PRIL => 0,
                        'prich' => 0,
                        ResultDataUnit::MORF_DEEPR => 0,
                        ResultDataUnit::MORF_CHISL => 0,
                        ResultDataUnit::MORF_MEST => 0,
                        'morf_undefined' => 0,
                        ResultDataUnit::SYNT_DOUBLE => 0,
                        ResultDataUnit::SYNT_DOPOL => 0,
                        ResultDataUnit::SYNT_OBSTOYT => 0,
                        ResultDataUnit::SYNT_OPREDEL => 0,
                        ResultDataUnit::SYNT_UNDERLINE => 0,
                        'not_set' => 0,
                        'synt_undefined' => 0,
                        'summary' => 0,

                    ];
                }

                if ($resultUnit->morf === 'undefined') {
                    $stat[$resultUnit->word]['morf_undefined'] += 1;
                } else {
                    $stat[$resultUnit->word][$resultUnit->morf] += 1;
                }

                if ($resultUnit->synt === 'undefined') {
                    $stat[$resultUnit->word]['synt_undefined'] += 1;
                } else {
                    $stat[$resultUnit->word][$resultUnit->synt] += 1;
                }
                $stat[$resultUnit->word]['summary'] += 1;
            }
        }

        foreach ($stat as $word => $wordStat) {
            $resultDataStat = new ResultDataStat();
            $resultDataStat->result_id = $this->id;
            $resultDataStat->word = $word;
            $resultDataStat->sysh = $wordStat[ResultDataUnit::MORF_SYSH];
            $resultDataStat->glag = $wordStat[ResultDataUnit::MORF_GLAG];
            $resultDataStat->nar = $wordStat[ResultDataUnit::MORF_NAR];
            $resultDataStat->soyz = $wordStat[ResultDataUnit::MORF_SOYZ];
            $resultDataStat->predlog = $wordStat[ResultDataUnit::MORF_PREDLOG];
            $resultDataStat->pril = $wordStat[ResultDataUnit::MORF_PRIL];
            $resultDataStat->deepr = $wordStat[ResultDataUnit::MORF_DEEPR];
            $resultDataStat->chisl = $wordStat[ResultDataUnit::MORF_CHISL];
            $resultDataStat->mest = $wordStat[ResultDataUnit::MORF_MEST];
            $resultDataStat->morf_undefined = $wordStat['morf_undefined'];
            $resultDataStat->synt_undefined = $wordStat['synt_undefined'];
            $resultDataStat->not_set = $wordStat['not_set'];
            $resultDataStat->double = $wordStat['double'];
            $resultDataStat->underline = $wordStat['underline'];
            $resultDataStat->dopol = $wordStat['dopol'];
            $resultDataStat->obstoyt = $wordStat['obstoyt'];
            $resultDataStat->opredel = $wordStat['opredel'];
            $resultDataStat->summary = $wordStat['summary'];

            $resultDataStat->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    private function ResolveMorf($externalMorf) {
        switch ($externalMorf) {
            case 'прилагательное':
                return 'pril';
            case 'причастие':
                return 'prich';
            case 'деепричастие':
                return 'deepr';
            case 'глагол':
                return 'glag';
            case 'существительное':
                return 'sysh';
            case 'наречие':
                return 'nar';
            case 'числительное':
                return 'chisl';
            case 'союз':
                return 'soyz';
            case 'предлог':
                return 'predlog';
            case 'местоимение':
                return 'mest';
            case 'пунктуация':
                return ResultDataUnit::PUNCTUATION;
            default:
                return 'undefined';
        }
    }

    private function ResolveSynt($externalMorf) {
        switch ($externalMorf) {
            case 'определение':
                return 'opredel';
            case 'обстоятельство':
                return 'obstoyt';
            case 'сказуемое':
                return 'double';
            case 'подлежащее':
                return 'underline';
            case 'дополнение':
                return 'dopol';
            case 'не определено':
                return 'undefined';
            case 'не является':
                return 'not_set';
            default:
                return 'undefined';
        }
    }
}
