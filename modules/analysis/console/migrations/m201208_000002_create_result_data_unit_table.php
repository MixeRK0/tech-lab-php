<?php

use yii\db\Migration;

class m201208_000002_create_result_data_unit_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('result_data_unit', [
            'id' => $this->primaryKey(),
            'result_id' => $this->integer(),
            'word' => $this->string(),
            'morf' => $this->string(),
            'synt' => $this->string(),
        ]);

        $this->createIndex(
            'idx-result_data_unit-result_id',
            'result_data_unit',
            'result_id'
        );

        $this->addForeignKey(
            'fk-result_data_unit-result_id',
            'result_data_unit',
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
        $this->dropTable('result_data_unit');
    }
}
