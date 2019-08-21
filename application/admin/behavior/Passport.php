<?php

namespace app\admin\behavior;

use yjy\Passport\Passport as MPassport;

class Passport
{

    public function __construct()
    {
    }

    public function createToken(&$token)
    {
        $tokenModel = MPassport::tokenModel();
        $tokenModel::where('user_id', '=', $token->user_id)
            ->where('client_id', '=', $token->client_id)
            ->where('name', '=', $token->name)
            ->where('id', '<>', $token->id)
            ->delete();
    }

}
