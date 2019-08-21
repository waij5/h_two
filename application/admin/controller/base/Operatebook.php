<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 手术预约管理
 *
 * @icon fa fa-circle-o
 */
class Operatebook extends Backend
{
    
    /**
     * OperateBook模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('OperateBook');

    }

    

}
