<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Validate;
use yjy\Passport\TokenGuard;

class Passport extends Controller
{
    public function login()
    {
        if ($this->request->isPost()) {
            $url      = $this->request->post('url', 'index/index');
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $authType = $this->request->post('authType', 'app');
            if (!in_array($authType, ['app', 'web'])) {
                $this->error('不正确的授权类型');
            }
            // $token     = $this->request->post('__token__');
            $rule = [
                'username' => 'require|length:3,30',
                'password' => 'require|length:3,30',
                // '__token__' => 'token',
            ];
            $data = [
                'username' => $username,
                'password' => $password,
                // '__token__' => $token,
            ];
            $validate = new Validate($rule);
            $result   = $validate->check($data);
            if (!$result) {
                // ['token' => $this->request->token()]
                $this->error($validate->getError(), $url, []);
            }
            \app\admin\model\AdminLog::setTitle('Api get token');

            $admin = \app\admin\model\Admin::get(['username' => $username]);
            if (!$admin || $admin->status != 'normal' || $admin->password != md5(md5($password) . $admin->salt)) {
                $this->error('授权失败', $url, []);
            }

            $token = $admin->createToken(1, $authType, [], $expiration = date('Y-m-d', strtotime('+30 days')));

            if ($token) {
                $key     = \think\Config::get('api_encrpyt_key');
                $time    = time(); //当前时间
                $payload = [
                    // 'iat' => $time,
                    // 'nbf' => $time,
                    // 'exp' => $time + 60 * 60 * 30,
                    'aud'    => '',
                    'sub'    => $admin->id,
                    'scopes' => '',
                    'jti'    => $token['id'],
                ];

                $accessToken = TokenGuard::generateJWT($payload);
                $this->success('授权成功', $url, ['url' => $url, 'id' => $admin->id, 'username' => $admin->username, 'nickname' => $admin->nickname, 'avatar' => $admin->avatar, 'position' => $admin->position, 'accessToken' => $accessToken]);
            }

            $this->error('授权失败', $url, []);
        }
    }
}
