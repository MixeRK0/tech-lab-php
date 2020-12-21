<?php

use yii\db\Migration;

class m201208_000001_create_result_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('result', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-result-user_id',
            'result',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('result');
    }
}
