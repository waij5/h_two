<?php

namespace app\admin\controller\proreport;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 仓库盘点
 *
 * @icon fa fa-circle-o
 */
class Changedetail extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $protypes = model('Protype')->where(['status'=>'normal','pid'=>'0'])->order('id', 'asc')->select();
        $protypesons = model('Protype')->where('pid > 0 ')->where('status','normal')->order('id', 'asc')->select();
		foreach ($protypes as $k => $v)
        {
            $protype[$v['id']] = $v;
        }
        foreach ($protypesons as $k => $v)
        {
            $protypeson[$v['id']] = $v;
        }
        $this->view->assign("depotList", $depotList);
        $this->view->assign("protype", $protype);
        $this->view->assign("protypeson", $protypeson);
        $this->view->assign("typeC", model('Changedetail')->getTypeC());
    }



    public function index(){
    	$data = [];
    	$where = [];
    	if($this->request->isPost()){
    		$postData = $this->request->post();
    		if($postData['stime'] && $postData['etime']){
        		$censusDate = '查询日期：'.$postData['stime'].' 至 '.$postData['etime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['sl.l_time'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
        	}
        	if($postData['pdutype_id']){
        		$where['pt.pdutype_id'] = $postData['pdutype_id'];
        	}
        	if($postData['pdutype2_id']){
        		$where['pt.pdutype2_id'] = $postData['pdutype2_id'];
        	}
        	if($postData['depot_id']){
        		$where['pt.depot_id'] = $postData['depot_id'];
        	}
        	if($postData['p_num'] != null){
        		$where['pt.num'] = $postData['p_num'];
        	}
        	if($postData['p_name']){
        		$where['pt.name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if(is_numeric($postData['typec'])){
        		$where['sl.l_type'] = $postData['typec'];
        	}

    		$list = db('Stock_log')->alias('sl')

    				->field('sl.*,d.dept_name,p.name as pname,pt.name,pt.lotnum,pt.cost,pt.price,pt.num')
    				->join('yjy_deptment d','d.dept_id = sl.l_department', 'LEFT')
                    ->join(DB::getTable('Product'). ' pt','sl.l_pid = pt.id', 'LEFT')
                    ->join('yjy_protype p','p.id = pt.pdutype2_id', 'LEFT')
                    ->where($where)
                    ->order('sl.l_time','DESC')
                    ->select();
            

            
            foreach ($list as $k => $v) {
            	if(!isset($data[$v['l_pid']])){
            		$data[$v['l_pid']][] = $v;
            	}else{
            		$data[$v['l_pid']][] = $v;
            	}
            }
            $counts = [];
            foreach ($data as $key => $val) {
                $counts[$key] = count($val);
            }

            // var_dump($data);
            $i = '0';
            $this->view->assign('data',$data);
            $this->view->assign('counts',$counts);
            $this->view->assign('i',$i);
            $this->view->assign('where', json_encode($where));
    	}
    	

    	return $this->view->fetch();
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {

        $whereAddon = input('yjyWhere', '[]');
        $whereAddon = json_decode($whereAddon, true);

        return $this->commondownloadprocess('changedetail', 'Changedetail name', $whereAddon);
    }



}