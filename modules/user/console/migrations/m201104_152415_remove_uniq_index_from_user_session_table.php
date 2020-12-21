<?php

use yii\db\Migration;

class m201104_152415_remove_uniq_index_from_user_session_table extends Migration
{
    /**
     * @return bool|void
     */
    public function up()
    {
        $this->dropIndex('idx-user_id-user_ip-user_agent', 'user_session');
    }

    public function down()
    {
        $this->createIndex(
            'idx-user_id-user_ip-user_agent',
            'user_session',
            ['user_id', 'user_ip', 'user_agent'],
            true
        );
    }
}
