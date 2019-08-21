<?php

namespace Yjy\Passport;

use think\Model;

class Token extends Model
{
    protected $table = 'oauth_access_tokens';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $dateFormat         = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // public function getClient()
    // {
    //     $userModel = config('passport.user_model');
    //     return $userModel->find($this->user_id);
    // }

    // public function getUser()
    // {
    //     // return (config('passport.user_model'))::find($this->user_id);
    // }

    // /**
    //  * Determine if the token has a given scope.
    //  *
    //  * @param  string  $scope
    //  * @return bool
    //  */
    // public function can($scope)
    // {
    //     return in_array('*', $this->scopes) ||
    //     array_key_exists($scope, array_flip($this->scopes));
    // }

    // /**
    //  * Determine if the token is missing a given scope.
    //  *
    //  * @param  string  $scope
    //  * @return bool
    //  */
    // public function cant($scope)
    // {
    //     return !$this->can($scope);
    // }

    // /**
    //  * Revoke the token instance.
    //  *
    //  * @return bool
    //  */
    // public function revoke()
    // {
    //     return $this->save(['revoked' => true]);
    // }

    // /**
    //  * Determine if the token is a transient JWT token.
    //  *
    //  * @return bool
    //  */
    // public function transient()
    // {
    //     return false;
    // }
}
