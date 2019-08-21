<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use think\Controller;
use think\Request;
use app\admin\model\ArriveStatus;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Notice extends Backend
{
    protected $noNeedRight = ['index'];

    public function index() {
        return $this->view->fetch();
    }

}