<?php

namespace modules\core\db;

use yii\db\Migration;

class MigrationWithTimeAndUser extends Migration
{
    const PREFIX_DELETED_TABLE = 'deleted_table__';
    public $userTable = 'user';

    public function createTable($table, $columns, $options = null)
    {
        $columns = array_merge(
            $columns,
            [
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
            ]
        );

        parent::createTable($table, $columns, $options);

        $this->addForeignKey(
            "fk-{$table}-created_by",
            $table,
            'created_by',
            $this->userTable,
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk-{$table}-updated_by",
            $table,
            'updated_by',
            $this->userTable,
            'id',
            'SET NULL',
            'CASCADE'
        );
    }
}
