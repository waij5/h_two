<?php

namespace app\api\controller;

use app\common\controller\api;
use think\Request;

class Auth extends api
{
    public function create()
    {

    }

    public function store()
    {
        $userName = input('user_name');
        $password = input('password');

        
        return $this->success(Request::instance()->admin);
        
    }
}