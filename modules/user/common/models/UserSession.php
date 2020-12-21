<?php


namespace modules\user\common\models;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $user_agent
 * @property string $access_token
 *
 * @property User $user
 */
class UserSession extends ActiveRecord
{
    public static function createForExistUserWithIpAndUserAgent(User $user, $userIp, $userAgent): UserSession
    {
        $session = new self();
        $session->user_id = $user->getId();
        $session->user_ip = $userIp;
        $session->user_agent = $userAgent;

        return $session;
    }

    public static function tableName()
    {
        return 'user_session';
    }

    public static function findByToken($token): ?UserSession
    {
        return self::find()
            ->where(['access_token' => $token])
            ->one();
    }

    public function fields()
    {
        return [
            'id',
            'user_id',
            'access_token'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
