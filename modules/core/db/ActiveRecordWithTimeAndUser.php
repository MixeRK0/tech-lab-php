<?php

namespace modules\core\db;

use modules\user\common\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class ActiveRecordWithTimeAndUser extends ActiveRecord
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->refresh();
    }

    public function getCreatedBy()
    {
        $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getUpdatedBy()
    {
        $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::class,
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => 'updated_at',
                    'value' => new \yii\db\Expression("CURRENT_TIMESTAMP"),
                ],
                'blameable' => [
                    'class' => BlameableBehavior::class,
                    'createdByAttribute' => 'created_by',
                    'updatedByAttribute' => 'updated_by',
                ],
            ]
        );
    }
}