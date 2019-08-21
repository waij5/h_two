<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use fast\Tree;
use think\Cache;

/**
 * 产品分类管理
 *
 * @icon fa fa-circle-o
 */
class Pducat extends Backend
{
    
    /**
     * Pducat模型对象
     */
    protected $model = null;
    protected $secTree = [];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Pducat');

        $this->secTree = $this->model->getTree();


        $pduList = [0 => __('None')];
        foreach ($this->secTree as $k => $v)
        {
            $pduList[$v['id']] = $v['pdc_name'];
        }
        $this->view->assign('pduList', $pduList);

        $rows =  model('pdutype')->where('pdt_status','=','1')->column('pdt_name');
        $this->view->assign('rows', $rows);

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $list = $this->secTree;
            $total = count($this->secTree);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
         if($this->request->isPost())
         {
             $params = $this->request->post();
             $rows =  model('pdutype')->column('pdt_name');
             $a = $params['row']['pdc_zpttype'];
             
             $dataset = [];       
             $dataset['pdc_code'] = $params['row']['pdc_code'];
             $dataset['pdc_name'] = $params['row']['pdc_name'];
             $dataset['pdc_zpttype'] = $rows["$a"];//类型从pdutype表中获取
             $dataset['pdc_pid'] = $params['row']['pdc_pid'];
             $dataset['pdc_status'] = $params['row']['pdc_status'];
             $dataset['pdc_sort'] = $params['row']['pdc_sort'];
             $dataset['pdc_remark'] = $params['row']['pdc_remark'];

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
        $row = $this->model->get(['pdc_id' => $ids]);

        if(!$row)
            $this->error(__('No Results were found'));

        if($this->request->isPost())
        {
            $params = $this->request->post();

            $dataset = [];       
            $dataset['pdc_code'] = $params['row']['pdc_code'];
            $dataset['pdc_name'] = $params['row']['pdc_name'];
            $dataset['pdc_zpttype'] = $params['row']['pdc_zpttype'];
            $dataset['pdc_pid'] = $params['row']['pdc_pid'];
            $dataset['pdc_status'] = $params['row']['pdc_status'];
            $dataset['pdc_sort'] = $params['row']['pdc_sort'];
            $dataset['pdc_remark'] = $params['row']['pdc_remark'];

            $result = $row->save($dataset);
             if($result)
             {
                $this->success();
             }else
             {
                $this->error();
             }
        }

        $pdtList = model('pdutype')->where(['pdt_status' => 1])->column('pdt_name', 'pdt_id');
        $this->view->assign('pdtList', $pdtList);

        $this->view->assign("row", $row);
        return $this->view->fetch();
   }
    
    public function del($ids = "")
    {
        if ($ids)
        {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v)
            {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            }
            $delIds = array_unique($delIds);
            $count = $this->model->where('pdc_id', 'in', $delIds)->delete();
            if ($count)
            {
                Cache::rm('__menu__');
                $this->success();
            }
        }
        $this->error();
    }
   
}

    

