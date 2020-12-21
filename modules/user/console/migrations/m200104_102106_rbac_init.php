<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables.
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m200104_102106_rbac_init extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;


        $this->createTable($authManager->ruleTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            "idx-{$authManager->ruleTable}-name",
            $authManager->ruleTable,
            'name',
            true
        );

        $this->createTable($authManager->itemTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(127)->unique()->notNull(),
            'group_name' => $this->string(127),
            'group_description' => $this->text(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(127),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            "idx-{$authManager->itemTable}-name",
            $authManager->ruleTable,
            'name',
            true
        );

        $this->createIndex(
            "idx-{$authManager->itemTable}-type",
            $authManager->itemTable,
            'type'
        );

        $this->addForeignKey(
            "fk-{$authManager->itemTable}-rule_name-{$authManager->ruleTable}-name",
            $authManager->itemTable,
            'rule_name',
            $authManager->ruleTable,
            'name',
            'SET NULL',
            'CASCADE'
        );

        $this->createTable($authManager->itemChildTable, [
            'id' => $this->primaryKey(),
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ]);

        $this->createIndex(
            "idx-{$authManager->itemChildTable}-parent-and-child",
            $authManager->itemChildTable,
            ['parent', 'child'],
            true
        );

        $this->addForeignKey(
            "fk-{$authManager->itemChildTable}-parent-{$authManager->itemTable}-name",
            $authManager->itemChildTable,
            'parent',
            $authManager->itemTable,
            'name',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk-{$authManager->itemChildTable}-child-{$authManager->itemTable}-name",
            $authManager->itemChildTable,
            'child',
            $authManager->itemTable,
            'name',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
        ]);

        $this->createIndex(
            "idx-{$authManager->assignmentTable}-item_name-and-user_id",
            $authManager->assignmentTable,
            ['item_name', 'user_id'],
            true
        );

        $this->createIndex(
            "idx-{$authManager->assignmentTable}-user_id",
            $authManager->assignmentTable,
            ['user_id']
        );

        $this->addForeignKey(
            "fk-{$authManager->assignmentTable}-item_name-{$authManager->itemTable}-name",
            $authManager->assignmentTable,
            'item_name',
            $authManager->itemTable,
            'name',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}
