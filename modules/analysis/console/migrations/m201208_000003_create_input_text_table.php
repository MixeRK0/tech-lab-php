<?php

use yii\db\Migration;

class m201208_000003_create_input_text_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('input_text', [
            'id' => $this->primaryKey(),
            'result_id' => $this->integer(),
            'str_short' => $this->string(),
            'str' => $this->text(),
        ]);

        $this->createIndex(
            'idx-input_text-result_id',
            'input_text',
            'result_id'
        );

        $this->addForeignKey(
            'fk-input_text-result_id',
            'input_text',
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
        $this->dropTable('input_text');
    }
}
