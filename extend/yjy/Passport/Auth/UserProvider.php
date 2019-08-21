<?php

namespace yjy\Passport\Auth;

interface UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     */
    public function retrieveById($identifier);

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     */
    public function retrieveByToken($identifier, $token);

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken($user, $token);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     */
    public function retrieveByCredentials(array $credentials);

    /**
     * Validate a user against the given credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials($user, array $credentials);
}
