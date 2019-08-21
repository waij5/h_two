<?php

namespace yjy\Passport;

use yjy\Passport\TokenRepository;
use yjy\Passport\TokenUtil;

trait HasApiTokens
{
    /**
     * The current access token for the authentication user.
     *
     * @var \yjy\Passport\Token
     */
    protected $accessToken;

    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        $clientModel = Passport::clientModel();
        return $clientModel::where('user_id', $this->getPk())->select();
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        $tokenModel = Passport::tokenModel();
        return $tokenModel::where('user_id', $this->getPk())->orderBy('created_at', 'desc')->select();
    }

    /**
     * Get the current access token being used by the user.
     *
     * @return \yjy\Passport\Token|null
     */
    public function token()
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $scopes
     * @return \yjy\Passport\PersonalAccessTokenResult
     */
    public function createToken($clientId, $name, $scopes = [], $expiration = null)
    {
        $tryTimes = 5;
        if (empty($expiration)) {
            $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
        }
        do {
            $tokenRepository = new TokenRepository;
            try {
                $pk      = $this->getPk();
                $tokenId = TokenUtil::generateToken($clientId, $this->$pk, $name);
                $token   = $tokenRepository->create([
                    'id'         => $tokenId,
                    'user_id'    => $this->$pk,
                    'client_id'  => $clientId,
                    'name'       => $name,
                    'scopes'     => json_encode($scopes),
                    'revoked'    => false,
                    // 'created_at' => time(),
                    // 'updated_at' => time(),
                    'expires_at' => $expiration,
                ]);
                unset($tokenRepository);
                \think\hook::listen('yjy_passport_create_token', $token);
                return $token;
            } catch (\think\exception\PDOException $e) {
                $tryTimes--;
            }
        } while ($tryTimes);
    }

    /**
     * Set the current access token for the user.
     *
     * @param  \yjy\Passport\Token  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
