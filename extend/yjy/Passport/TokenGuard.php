<?php

namespace yjy\Passport;

use Exception;
use Firebase\JWT\JWT;
use think\Config;
use think\helper\Str;
use think\Request;
use yjy\Passport\Auth\UserProvider;
use yjy\Passport\BearerTokenValidator;

class TokenGuard
{

    /**
     * The token repository instance.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokens;

    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    /**
     * The encrypter implementation.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * Create a new token guard instance.
     *
     * @param  \League\OAuth2\Server\ResourceServer  $server
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(UserProvider $provider,
        TokenRepository $tokens, $encrypter) {
        $this->provider  = $provider;
        $this->tokens    = $tokens;
        $this->encrypter = $encrypter;
    }

    /**
     * Get the user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function user(Request $request)
    {
        if ($this->getBearerToken($request)) {
            return $this->authenticateViaBearerToken($request);
        }
        // elseif ($request->cookie(Passport::cookie())) {
        //     return $this->authenticateViaCookie($request);
        // }
    }

    public function getBearerToken($request)
    {
        $header = $request->header('Authorization', '');

        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }

    public static function generateJWT($payload)
    {
        return JWT::encode($payload, Config::get('passport.encrypt_key'), 'HS256');
    }

    /**
     * Authenticate the incoming request via the Bearer token.
     *
     * @param  \think\Request  $request
     * @return mixed
     */
    // ServerRequestInterface
    protected function authenticateViaBearerToken(Request $request)
    {
        $validator = new BearerTokenValidator;
        $validator->setPublicKey(Config::get('passport.encrypt_key'));
        $psr = $validator->validateAuthorization($request);

        // If the access token is valid we will retrieve the user according to the user ID
        // associated with the token. We will use the provider implementation which may
        // be used to retrieve users from Eloquent. Next, we'll be ready to continue.
        $user = $this->provider->retrieveById(
            $psr->oauth_user_id
        );

        if (!$user) {
            return;
        }

        // Next, we will assign a token instance to this user which the developers may use
        // to determine if the token has a given scope, etc. This will be useful during
        // authorization such as within the developer's Laravel model policy classes.
        $token = $this->tokens->find(
            $psr->oauth_access_token_id
        );

        $clientId = $psr->oauth_client_id;

        // Finally, we will verify if the client that issued this token is still valid and
        // its tokens may still be used. If not, we will bail out since we don't want a
        // user to be able to send access tokens for deleted or revoked applications.
        // if ($this->clients->revoked($clientId)) {
        //     return;
        // }

        return $token ? $user->withAccessToken($token) : null;
    }

    /**
     * Authenticate the incoming request via the token cookie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function authenticateViaCookie($request)
    {
        // If we need to retrieve the token from the cookie, it'll be encrypted so we must
        // first decrypt the cookie and then attempt to find the token value within the
        // database. If we can't decrypt the value we'll bail out with a null return.
        try {
            $token = $this->decodeJwtTokenCookie($request);
        } catch (Exception $e) {
            return;
        }

        // We will compare the CSRF token in the decoded API token against the CSRF header
        // sent with the request. If the two don't match then this request is sent from
        // a valid source and we won't authenticate the request for further handling.
        if (!Passport::$ignoreCsrfToken && (!$this->validCsrf($token, $request) ||
            time() >= $token['expiry'])) {
            return;
        }

        // If this user exists, we will return this user and attach a "transient" token to
        // the user model. The transient token assumes it has all scopes since the user
        // is physically logged into the application via the application's interface.
        if ($user = $this->provider->retrieveById($token['sub'])) {
            return $user->withAccessToken(new TransientToken);
        }
    }

    /**
     * Decode and decrypt the JWT token cookie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function decodeJwtTokenCookie($request)
    {
        return (array) JWT::decode(
            $this->encrypter->decrypt($request->cookie(Passport::cookie()), Passport::$unserializesCookies),
            $this->encrypter->getKey(), ['HS256']
        );
    }

    /**
     * Determine if the CSRF / header are valid and match.
     *
     * @param  array  $token
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function validCsrf($token, $request)
    {
        return isset($token['csrf']) && hash_equals(
            $token['csrf'], (string) $request->header('X-CSRF-TOKEN')
        );
    }
}
