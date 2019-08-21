<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Validate;
use yjy\Passport\TokenGuard;

/**
 * 后台首页
 * @internal
 */
class Index extends Backend
{

    protected $noNeedLogin = ['login', 'gettoken'];
    protected $noNeedRight = ['index', 'logout'];
    protected $layout      = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 后台首页
     */
    public function index()
    {
        //
        $menulist = $this->auth->getSidebar([
            'dashboard' => 'hot',
            'addon'     => ['new', 'red', 'badge'],
            'auth/rule' => 'side',
            'general'   => ['18', 'purple'],
        ], $this->view->site['fixedpage']);
        $this->view->assign('menulist', $menulist);
        $this->view->assign('title', __('Home'));
        if (\think\Session::get('isJustLogin')) {
            $this->view->assign('isJustLogin', 1);

            \think\Session::delete('isJustLogin');
        }

        return $this->view->fetch();
    }

    /**
     * 管理员登录
     */
    public function login()
    {
        $url = $this->request->get('url', 'index/index');
        if ($this->auth->isLogin()) {
            $this->error(__("You've logged in, do not login again"), $url);
        }
        if ($this->request->isPost()) {
            $username  = $this->request->post('username');
            $password  = $this->request->post('password');
            $keeplogin = $this->request->post('keeplogin');
            $token     = $this->request->post('__token__');
            $rule      = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:3,30',
                '__token__' => 'token',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                '__token__' => $token,
            ];
            $validate = new Validate($rule);
            $result   = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            \app\admin\model\AdminLog::setTitle(__('Login'));
            $result = $this->auth->login($username, $password, $keeplogin ? 86400 : 0);
            if ($result === true) {
                $this->success(__('Login successful'), $url, ['url' => $url, 'id' => $this->auth->id, 'username' => $username, 'avatar' => $this->auth->avatar]);
            } else {
                $this->error(__('Username or password is incorrect'), $url, ['token' => $this->request->token()]);
            }
        }

        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin()) {
            $this->redirect($url);
        }
        $background = cdnurl("/assets/img/loginbg.jpg");
        $this->view->assign('title', __('Sign in'));
        $this->view->assign('background', $background);
        \think\Session::set('isJustLogin', 1);
        \think\Hook::listen("login_init", $this->request);
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success(__('Logout successful'), 'index/login');
    }

    public function gettoken()
    {
        if ($this->request->isPost()) {
            $url      = $this->request->post('url', 'index/index');
            $username = $this->request->post('username');
            $password = $this->request->post('password');
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

            $token = $admin->createToken(1, 'app', [], $expiration = date('Y-m-d', strtotime('+30 days')));

            if ($token) {
                $key     = '344'; //key
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
                $this->success('授权成功', $url, ['url' => $url, 'id' => $admin->id, 'username' => $admin->username, 'nickname' => $admin->nickname, 'avatar' => $admin->avatar, 'accessToken' => $accessToken]);
            }

            $this->error('授权失败', $url, []);
        }
    }

}
