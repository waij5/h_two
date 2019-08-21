<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 产品登记
 *
 * @icon fa fa-circle-o
 */
class Stock extends Backend
{
    
    /**
     * Product模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Product');
        $this->view->assign("isJkList", $this->model->getIsJkList());
        $this->view->assign("drugTypeList", $this->model->getDrugTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("isTcList", $this->model->getIsTcList());
        $this->view->assign("isJfList", $this->model->getIsJfList());
        $this->view->assign("isCgList", $this->model->getIsCgList());
        $this->view->assign("typeList", $this->model->getTypeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model->alias('p')
					->field('p.*,d.name as dname')
                    ->join('yjy_depot d', 'd.id = p.depot_id')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $result = array("total" => $total, "rows" => $list);
            foreach ($list as $key => $value) {
                $value['totalprice'] = $value['price'] * $value['stock'];
            }

            return json($result);
        }
        return $this->view->fetch();
    }
    

    /**
     * 编辑
     */
    public function edit($ids=null)
    {
        // var_dump($ids);die();
        // $ids = $ids;
        if($this->request->isAjax()){
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $this->model = model('Stock');
            $total = $this->model->where('l_pid',$ids)->where($where)->order($sort, $order)->count();
            $list = $this->model->alias('s')
                    ->field('s.*,d.dept_name,p.proname')
                    ->join('yjy_deptment d','d.dept_id = s.l_department', 'LEFT')
                    ->join('yjy_producer p','p.id = s.l_producer', 'LEFT')
                    ->where('s.l_pid',$ids)
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $key => $value) {
                $value['l_type'] = model('Stock')->getType($value['l_type']);
            }
            $result = array("total" => $total,"rows" => $list);
            return json($result);
        }
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

}
