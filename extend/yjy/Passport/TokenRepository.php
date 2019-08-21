<?php

namespace yjy\Passport;

use think\Model;
use yjy\Passport\Passport;

class TokenRepository
{
    /**
     * Creates a new Access Token.
     *
     * @param  array  $attributes
     * @return \yjy\Passport\Token
     */
    public function create($attributes)
    {
        $token = Passport::token();
        return $token->create($attributes);
    }

    /**
     * Get a token by the given ID.
     *
     * @param  string  $id
     * @return \yjy\Passport\Token
     */
    public function find($id)
    {
        $tokenModel = Passport::tokenModel();
        return $tokenModel::find($id);
    }

    /**
     * Get a token by the given user ID and token ID.
     *
     * @param  string  $id
     * @param  int  $userId
     * @return \yjy\Passport\Token|null
     */
    public function findForUser($id, $userId)
    {
        $tokenModel = Passport::tokenModel();
        return $tokenModel::where('id', $id)->where('user_id', $userId)->find();
    }

    /**
     * Get the token instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser($userId)
    {
        $tokenModel = Passport::tokenModel();
        return $tokenModel::where('user_id', $userId)->select();
    }

    /**
     * Get a valid token instance for the given user and client.
     *
     * @param  \think\Model  $user
     * @param  \yjy\Passport\Client  $client
     * @return \yjy\Passport\Token|null
     */
    public function getValidToken($user, $client)
    {
        $tokenModel = Passport::tokenModel();
        $pk         = $user->getPk();
        return $tokenModel::where('client_id', '=', $client->id)
            ->where('user_id', '=', $user->$pk)
            ->where('revoked', '=', 0)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->find();
    }

    /**
     * Store the given token instance.
     *
     * @param  \yjy\Passport\Token  $token
     * @return void
     */
    public function save(Token $token)
    {
        $token->save();
    }

    /**
     * Revoke an access token.
     *
     * @param  string  $id
     * @return mixed
     */
    public function revokeAccessToken($id)
    {
        $tokenModel = Passport::tokenModel();
        return $tokenModel::where('id', $id)->update(['revoked' => true]);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param  string  $id
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($id)
    {
        if ($token = Passport::token()->find($id)) {
            return $token->revoked;
        }

        return true;
    }

    /**
     * Find a valid token for the given user and client.
     *
     * @param  \think\Model  $user
     * @param  \yjy\Passport\Client  $client
     * @return \yjy\Passport\Token|null
     */
    public function findValidToken($user, $client)
    {
        $tokenModel = Passport::tokenModel();
        $pk         = $user->getPk();
        return $tokenModel::where('client_id', '=', $client->id)
            ->where('user_id', '=', $user->$pk)
            ->where('revoked', '=', 0)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->orderBy('expires_at', 'desc')
            ->find();
    }
}
