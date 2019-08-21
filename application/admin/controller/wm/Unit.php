<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 产品单位设置
 *
 * @icon fa fa-circle-o
 */
class Unit extends Backend
{
    
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Unit');
        $this->view->assign("statusList", $this->model->getStatusList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            // var_dump($params['code']);
            $data=[];
            if ($params)
            {
                $data['code'] = $params['code'];
                $data['name'] = $params['name'];
                $data['status'] = $params['status'];
                $data['remark'] = $params['remark'];
                $editRes= db('unit')->where('id',$ids)->update($data);
                if($editRes){
                    $this->success();
                }else{
                    $this->error();
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
