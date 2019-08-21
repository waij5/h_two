<?php
/**
 * 部门权限类 直接下属, 所有下属, 所有
 * singleton mode, please use SecAuth::instance to init
 * Author: Leekaen
 */
namespace yjy\Passport;

class TokenUtil
{
    const RANDOM_POOL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function generateToken($cliendId, $userId, $name)
    {
        return $token = static::generateUniqueIdentifier(40) . md5($cliendId . '_' . $userId . '_' . time()) . static::generateUniqueIdentifier(8);
    }

    protected static function generateUniqueIdentifier($length = 40)
    {
        try {
            return substr(str_shuffle(str_repeat(static::RANDOM_POOL, ceil($length / strlen(static::RANDOM_POOL)))), 0, $length);
        } catch (\Exception $e) {
            throw new \Exception('Could not generate a random string', $e);
        }
    }

}
