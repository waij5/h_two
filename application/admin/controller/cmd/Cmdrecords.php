<?php

namespace app\admin\controller\cmd;

use app\common\controller\Backend;
use think\Controller;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Cmdrecords extends Backend
{

    /**
     * CmdRecords模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CmdRecords');
    }

    public function add()
    {
        $this->error('Access denied');
    }

    public function edit($ids = null)
    {
        $this->error('Access denied');
    }

    public function del($ids = "")
    {
        $this->error('Access denied');
    }

    public function multi($ids = "")
    {
        $this->error('Access denied');
    }

}
