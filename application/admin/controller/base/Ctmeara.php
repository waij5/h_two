<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 区域来源管理
 *
 * @icon fa fa-circle-o
 */
class Ctmeara extends Backend
{
    
    /**
     * Ctmeara模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Ctmeara');

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
             
             if(empty($params['area'])){
                $params['ear_area']=$params['province'].'-'.$params['city'];
             }else{
                $params['ear_area']=$params['province'].'-'.$params['city'].'-'.$params['area'];
             }
             // $params['ear_area']=$params['province'].'-'.$params['city'].'-'.$params['area'];

             $dataset = [];       
             $dataset['ear_code'] = $params['row']['ear_code'];
             $dataset['ear_area'] = $params['ear_area'];
             $dataset['ear_name'] = $params['row']['ear_name'];
             $dataset['ear_spell'] = $params['row']['ear_spell'];
             $dataset['ear_status'] = $params['row']['ear_status'];
             $dataset['ear_remark'] = $params['row']['ear_remark'];
             $result = $this->model->create($dataset);
             if($result)
             {
                $this->success();
             }else
             {
                $this->error();
             }
         }
         return $this->view->fetch();
   }
    
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['ear_id' => $ids]);
        $res = explode("-",$row["ear_area"]);
        $this->view->assign("res", $res);
        if (!$row)
            $this->error(__('No Results were found'));
        if($this->request->isPost())
         {
             $params = $this->request->post();

             if(empty($params['area'])){
                $params['ear_area']=$params['province'].'-'.$params['city'];
             }else{
                $params['ear_area']=$params['province'].'-'.$params['city'].'-'.$params['area'];
             }
             
             $dataset = [];       
             $dataset['ear_code'] = $params['row']['ear_code'];
             $dataset['ear_area'] = $params['ear_area'];
             $dataset['ear_name'] = $params['row']['ear_name'];
             $dataset['ear_spell'] = $params['row']['ear_spell'];
             $dataset['ear_status'] = $params['row']['ear_status'];
             $dataset['ear_remark'] = $params['row']['ear_remark'];
             // $result = $this->model->create($dataset);
             $result = $row->save($dataset);
             if($result)
             {
                $this->success();
             }else
             {
                $this->error();
             }
         }
         $this->view->assign("row", $row);
         return $this->view->fetch();
    }

}
