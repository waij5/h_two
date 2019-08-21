<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Controller;
use think\Request;


class Download extends Backend
{
    protected $noNeedLogin = ['help'];
    
    public function index()
    {
        return $this->view->fetch();
    }

    public function help()
    {
        return $this->view->fetch();
    }
    
}
