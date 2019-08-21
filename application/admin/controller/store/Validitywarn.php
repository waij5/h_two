<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 产品登记
 *
 * @icon fa fa-circle-o
 */
class Validitywarn extends Backend
{
    
    /**
     * Product模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PurchaseFlow');
        // $this->view->assign("isJkList", $this->model->getIsJkList());
        // $this->view->assign("drugTypeList", $this->model->getDrugTypeList());
        // $this->view->assign("statusList", $this->model->getStatusList());
        // $this->view->assign("isTcList", $this->model->getIsTcList());
        // $this->view->assign("isJfList", $this->model->getIsJfList());
        // $this->view->assign("isCgList", $this->model->getIsCgList());
        // $this->view->assign("typeList", $this->model->getTypeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
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

            

            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime']);       //今天的起始时间
                $endr= strtotime($filter['etime']);   // 90天后的结束时间
            }else{
                $startr= mktime(0,0,0,date('m'),date('d'),date('Y'));       //今天的起始时间
                $endr= mktime(23,59,59,date('m'),date('d')+90,date('Y'));   // 90天后的结束时间
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // $map['p.extime'] = array("between",array($startr,$endr));
            $map['p.extime'] = array("between",array($startr,$endr));
            // var_dump($filter);die();
            // var_dump($map);
            // if(!$where){
            //     $startr='1509984000';
            // }else {
            //    $startr='1509638400';
            // }

// $endr= '1518278400';
// dump($where);die();
            // $startr= mktime(0,0,0,date('m'),date('d'),date('Y'));       //今天的起始时间
            // $endr= mktime(23,59,59,date('m'),date('d')+90,date('Y'));   // 90天后的结束时间
            // ->alias('pf')
            
            // $total =db('product')->where($map)
            // // ->where($where)
            //         ->order($sort, $order)
            //         ->limit($offset, $limit)
            //         ->count();
            // $list = db('product')->where($map)
            // // ->where($where)
            //         ->order($sort, $order)
            //         ->limit($offset, $limit)
            //         ->select();

            $total =db('product')->alias('p')->field('p.*,d.name as depot_name')
                    ->join(DB::getTable('depot') . ' d', 'p.depot_id = d.id', 'LEFT')
                    ->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->count();
            
            $list = db('product')->alias('p')->field('p.*,d.name as depot_name')
                    ->join(DB::getTable('depot') . ' d', 'p.depot_id = d.id', 'LEFT')
                    ->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $list = model('Product')->getchineseName($list);     ////获取所属科目、类别中文名称名称 
            // $list = model('Product')->getOrderNumber($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
