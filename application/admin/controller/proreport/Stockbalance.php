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
class Stockbalance extends Backend
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

            if($postData['newsort'] =='1' || $postData['newsort'] ==''){
                $newSort = 'p.pdutype2_id';
                // var_dump($newSort);
            }elseif ($postData['newsort'] =='2') {
                $newSort = 'p.code';
                // var_dump($newSort);
            }
            

        	$list = db('Stock_log')->alias('sl')
    				->field('sl.*,p.name,p.num,p.sizes,p.unit,p.lotnum,p.cost,p.price,p.stock,pe.name as pename,p.pdutype_id,p.pdutype2_id')
        			->join(DB::getTable('Product'). ' p','sl.l_pid = p.id', 'LEFT')
        			->join(DB::getTable('Protype'). ' pe','p.pdutype2_id = pe.id', 'LEFT')
        			->where($where)
        			->order($newSort,'ASC')
        			->select();
            $data = [];
            $totalData = [];
            if($list){
                $list = model('Product')->getchineseName($list);

            
                $data = model('Changepool')->dealArr($list);

                foreach ($data as $k => $v) {
                    $data[$k]['beginStock'] = $v['stock']-$v['jh']+$v['scjh']-$v['dbrk']+$v['scdbrk']-$v['pyrk']+$v['scpyrk']-$v['thrk']+$v['scthrk']-$v['qtrk']+$v['scqtrk']+$v['rkcj']-$v['scrkcj']+$v['pkcj']-$v['scpkcj']+$v['gqcj']-$v['scgqcj']+$v['qtcj']-$v['scqtcj']+$v['fy']-$v['cy']+$v['ly']-$v['scly']+$v['ll']-$v['scll'];
                }
                
                
                $totalData['beginCost']='';
                $totalData['lastCost']='';
                $totalData['beginPrice']='';
                $totalData['lastPrice']='';
                foreach ($data as $k => $v) {
                    $totalData['beginCost'] += $v['beginStock']*$v['cost'];
                    $totalData['beginPrice'] += $v['beginStock']*$v['price'];
                    $totalData['lastCost'] += $v['stock']*$v['cost'];
                    $totalData['lastPrice'] += $v['stock']*$v['price'];
                }
                $totalData['totalNum'] = count($data);
            }
			
			// var_dump($totalData);
			$this->view->assign('data',$data);
			$this->view->assign('totalData',$totalData);

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

        return $this->commondownloadprocess('stockbalance', 'Stockbalance name', $whereAddon);
    }



}