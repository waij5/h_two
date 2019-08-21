<?php

namespace yjy\Passport;

use Firebase\JWT\JWT;
use think\Request;
use yjy\Passport\Exception\OAuthServerException;

class BearerTokenValidator
{
    // use CryptTrait;

    /**
     * @var AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * @var CryptKey
     */
    protected $publicKey;

    /**
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct()
    {
        // $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * Set the public key
     *
     * @param CryptKey $key
     */
    public function setPublicKey($key)
    {
        $this->publicKey = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthorization(Request $request)
    {
        $headers = $request->header();
        if (isset($headers['authorization']) == false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $authorization = $headers['authorization'];
        try {
            $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $authorization));
            // Attempt to parse and validate the JWT
            $leeway = 120;
            $token = JWT::decode($jwt, $this->publicKey, ['HS256']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            throw OAuthServerException::accessDenied('Access token could not be verified');
        } catch (\Exception $e) {
            print_r($e);
            throw OAuthServerException::accessDenied('Access token is invalid');
        }

        $request->bind('oauth_access_token_id', isset($token->jti) ? $token->jti : '');
        $request->bind('oauth_client_id', isset($token->aud) ? $token->aud : '');
        $request->bind('oauth_user_id', isset($token->sub) ? $token->sub : '');
        $request->bind('oauth_scopes', isset($token->scopes) ? $token->scopes : '');

        return $request;
    }
}
