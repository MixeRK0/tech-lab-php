<?php

use yii\db\Migration;

class m200515_123411_add_user_session_table extends Migration
{
    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable(
            'user_session',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer(),
                'user_ip' => $this->string(16)->notNull(),
                'user_agent' => $this->string(),
                'access_token' => $this->string(32)->notNull()
            ]
        );

        $this->addForeignKey(
            'fk-user_session-user',
            'user_session',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-user_id',
            'user_session',
            'user_id',
            false
        );

        $this->createIndex(
            'idx-access_token',
            'user_session',
            'access_token',
            true
        );

        $this->createIndex(
            'idx-user_id-user_ip-user_agent',
            'user_session',
            ['user_id', 'user_ip', 'user_agent'],
            true
        );

        $this->dropColumn('user', 'access_token');
    }

    public function down()
    {
        $this->addColumn(
            'user',
            'access_token',
            $this->string(32)
        );

        $this->dropTable('user_session');
    }
}
