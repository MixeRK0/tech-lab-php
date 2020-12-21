<?php

class m130524_201442_init extends \modules\core\db\MigrationWithTimeAndUser
{
    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->smallInteger()->notNull()->defaultValue(\modules\user\common\models\User::ROLE_GUEST),
            'status' => $this->smallInteger()->notNull()->defaultValue(\modules\user\common\models\User::STATUS_ACTIVATED),

            'access_token' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
        ]);

        $this->insert(
            'user',
            [
                'id' => 1,
                'email' => \Yii::$app->params['admin-credentials']['email'],
                'role' => \modules\user\common\models\User::ROLE_ADMIN,
                'status' => \modules\user\common\models\User::STATUS_ACTIVATED,
                'access_token' => Yii::$app->security->generateRandomString(),
                'password_hash' => \Yii::$app->security->generatePasswordHash(
                    \Yii::$app->params['admin-credentials']['password']
                ),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
