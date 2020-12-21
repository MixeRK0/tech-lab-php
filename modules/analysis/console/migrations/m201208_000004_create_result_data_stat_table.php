<?php

use yii\db\Migration;

class m201208_000004_create_result_data_stat_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('result_data_stat', [
            'id' => $this->primaryKey(),
            'result_id' => $this->integer(),
            'word' => $this->string(),
            'sysh' => $this->integer(),
            'glag' => $this->integer(),
            'nar' => $this->integer(),
            'soyz' => $this->integer(),
            'predlog' => $this->integer(),
            'pril' => $this->integer(),
            'prich' => $this->integer(),
            'deepr' => $this->integer(),
            'chisl' => $this->integer(),
            'mest' => $this->integer(),
            'morf_undefined' => $this->integer(),
            'synt_undefined' => $this->integer(),
            'double' => $this->integer(),
            'underline' => $this->integer(),
            'dopol' => $this->integer(),
            'obstoyt' => $this->integer(),
            'opredel' => $this->integer(),
            'summary' => $this->integer(),
            'not_set' => $this->integer()
        ]);

        $this->createIndex(
            'idx-result_data_stat-result_id',
            'result_data_stat',
            'result_id'
        );

        $this->addForeignKey(
            'fk-result_data_stat-result_id',
            'result_data_stat',
            'result_id',
            'result',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('result_data_stat');
    }
}
