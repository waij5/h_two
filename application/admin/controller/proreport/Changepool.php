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
class Changepool extends Backend
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
    }
    public function index(){
    	if($this->request->isPost()){
        	$postData = $this->request->post();

        	$where = [];
        	if($postData['stime'] && $postData['etime']){
        		$censusDate = '查询日期：'.$postData['stime'].' 至 '.$postData['etime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['sl.l_time'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
        	}
        	if($postData['pdutype_id']){
        		$where['p.pdutype_id'] = $postData['pdutype_id'];
        	}
        	if($postData['pdutype2_id']){
        		$where['p.pdutype2_id'] = $postData['pdutype2_id'];
        	}
        	if($postData['depot_id']){
        		$where['p.depot_id'] = $postData['depot_id'];
        	}
        	if($postData['p_num'] != null){
        		$where['p.num'] = $postData['p_num'];
        	}
        	if($postData['p_name']){
        		$where['p.name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}

        	$list = db('Stock_log')->alias('sl')
    				->field('sl.*,p.name,p.num,p.sizes,p.unit,p.lotnum,p.cost,p.price,p.stock,pe.name as pename,p.pdutype_id,p.pdutype2_id')
        			->join(DB::getTable('Product'). ' p','sl.l_pid = p.id', 'LEFT')
        			->join(DB::getTable('Protype'). ' pe','p.pdutype2_id = pe.id', 'LEFT')
        			->where($where)
        			->order('p.pdutype2_id','DESC')
        			->select();
			$list = model('Product')->getchineseName($list);

			$datas = [];
			$datas = model('Changepool')->dealArr($list);
			
        			// var_dump($list);
			$this->view->assign('data',$datas);

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

        return $this->commondownloadprocess('changepool', 'Changepool name', $whereAddon);
    }

}