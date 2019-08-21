<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\Chntype;


/**
 * 渠道名称资料管理
 *
 * @icon fa fa-circle-o
 */
class Ctmchannels extends Backend
{
    
    /**
     * Ctmchannels模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Ctmchannels');

        //类型(营销渠道管理)
        $chnTypeLists = model('exprtype')->field('ept_id,ept_name')->select();
        $chnTypeList = ['' => __('NONE')];
        foreach ($chnTypeLists as  $value) {
            $chnTypeList[$value['ept_id']] = $value['ept_name'];
        }
        $this->view->assign('chnTypeList', $chnTypeList);

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    public function add()
    {
        if($this->request->isPost())
         {
             $params = $this->request->post();
             $admin = \think\Session::get('admin');
            
             $dataset = [];
             $dataset['chn_code'] = $params['row']['chn_code'];
             $dataset['chn_name'] = $params['row']['chn_name'];
             $dataset['chn_type'] = $params['row']['chn_type'];
             $dataset['chn_uid'] =  $admin['id'];
             $dataset['chn_status'] = $params['row']['chn_status'];
             $dataset['chn_sort'] = $params['row']['chn_sort'];
             $dataset['chn_remark'] = $params['row']['chn_remark'];

             $result = $this->model->create($dataset);
             if($result)
             {
                $this->success();
             }else
             {
                $this->error();
             } 

         }
         // $this->view->assign('chnTypeList', Chntype::getList());
         return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get(['chn_id' => $ids]);

        if(!$row)
            $this->error(__('No Results were found'));

        if($this->request->isPost())
        {
            $params = $this->request->post();

             $dataset = [];       
             $dataset['chn_code'] = $params['row']['chn_code'];
             $dataset['chn_name'] = $params['row']['chn_name'];
             $dataset['chn_type'] = $params['row']['chn_type'];
             $dataset['chn_status'] = $params['row']['chn_status'];
             $dataset['chn_sort'] = $params['row']['chn_sort'];
             $dataset['chn_remark'] = $params['row']['chn_remark'];

            $result = $row->save($dataset);
             if($result !== false)
             {
                $this->success();
             }else
             {
                $this->error();
             }
        }
        // $this->view->assign('chnTypeList', Chntype::getList());
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
