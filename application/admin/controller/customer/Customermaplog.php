<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\CustomerMap;


/**
 *
 * @icon fa fa-circle-o
 */
class Customermaplog extends Backend
{
    
    /**
     * Ctmsource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CustomerMapLog');

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    //同步
    public function synchronization($ids = '')
    {
         $ids = explode(',', $ids);
        $this->model->update(['status' => 1], ['id' => ['in', $ids]]);
        $this->success();
    }

    public function add()
    {
        $this->error(__('You have no permission'));
    }

    public function edit($ids = '')
    {
        $this->error(__('You have no permission'));
    }

    public function del($ids = '')
    {
        $this->error(__('You have no permission'));
    }
}
