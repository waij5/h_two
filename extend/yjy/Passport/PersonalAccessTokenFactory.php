<?php

namespace Yjy\Passport;

// use League\OAuth2\Server\AuthorizationServer;
use Firebase\JWT\JWT;

class PersonalAccessTokenFactory
{
    /**
     * The client repository instance.
     *
     */
    protected $clients;

    /**
     * The token repository instance.
     *
     */
    protected $tokens;

    /**
     * The JWT token parser instance.
     *
     */
    protected $jwt;

    /**
     * Create a new personal access token factory instance.
     *
     * @return void
     */
    public function __construct(TokenRepository $tokens) {
        $this->tokens  = $tokens;
    }

    /**
     * Create a new personal access token.
     *
     * @param  mixed  $userId
     * @param  string  $name
     * @param  array  $scopes
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function make($userId, $name, array $scopes = [])
    {
        $response = $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($this->clients->personalAccessClient(), $userId, $scopes)
        );

        $this->clients->personalAccessClient();

        $token = tap($this->findAccessToken($response), function ($token) use ($userId, $name) {
            $this->tokens->save($token->forceFill([
                'user_id' => $userId,
                'name'    => $name,
            ]));
        });

        return new PersonalAccessTokenResult(
            $response['access_token'], $token
        );
    }

    /**
     * Create a request instance for the given client.
     *
     * @param  \Laravel\Passport\Client  $client
     * @param  mixed  $userId
     * @param  array  $scopes
     * @return \Zend\Diactoros\ServerRequest
     */
    protected function createRequest($client, $userId, array $scopes)
    {
        return (new ServerRequest)->withParsedBody([
            'grant_type'    => 'personal_access',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'user_id'       => $userId,
            'scope'         => implode(' ', $scopes),
        ]);
    }

    // /**
    //  * Dispatch the given request to the authorization server.
    //  *
    //  * @param  \Zend\Diactoros\ServerRequest  $request
    //  * @return array
    //  */
    // protected function dispatchRequestToAuthorizationServer(ServerRequest $request)
    // {
    //     return json_decode($this->server->respondToAccessTokenRequest(
    //         $request, new Response
    //     )->getBody()->__toString(), true);
    // }

    /**
     * Get the access token instance for the parsed response.
     *
     * @param  array  $response
     * @return Token
     */
    protected function findAccessToken(array $response)
    {
        return $this->tokens->find(
            JWT::decode($jwt, $key, array('HS256'));
            // $this->jwt->parse($response['access_token'])->getClaim('jti')
        );
    }
}
