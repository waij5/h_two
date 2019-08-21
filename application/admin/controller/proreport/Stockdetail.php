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
class Stockdetail extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PurchaseOrder');
        $producerList = [];
		$depotList = [];
		$producerLists = model('Producer')->where('status','normal')->order('id', 'asc')->select();
		foreach ($producerLists as $k => $v)
        {
            $producerList[$v['id']] = $v;
        }
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $this->view->assign("typeList", model('StockDetail')->getTypeList());
        $this->view->assign("depotList", $depotList);
        $this->view->assign("producerList", $producerList);
        // $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function index(){
        if($this->request->isPost()){

        	$postData = $this->request->post();
        	// var_dump($where['depot_id']);
        	$where = [];
        	if($postData['sintime'] && $postData['eintime']){
        		$censusDate = '统计日期：'.$postData['sintime'].' 至 '.$postData['eintime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['po.createtime'] = ['between',[strtotime($postData['sintime'].'00:00:00'),strtotime($postData['eintime'].'23:59:59')]];	//入库日期."00:00".":00"."23:59".":59" 
        	}
        	if($postData['p_num'] != null){
        		$where['pt.num'] = $postData['p_num'];		//产品编号
        	}
        	if($postData['p_name']){
        		$where['pt.name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if($postData['lotnum'] != null){
        		$where['pt.lotnum'] = $postData['lotnum'];		//产品编号
        	}
        	if($postData['depot_id'] != null){
        		$where['po.depot_id'] = $postData['depot_id'];		//仓库
        	}
        	if($postData['stime'] && $postData['etime']){
        		$where['pf.expirestime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];		//有效日期
        	}
        	if($postData['order_num'] != null){
        		$where['po.order_num'] = $postData['order_num'];		//单号
        	}
        	if($postData['producer_id'] != null){
        		$where['po.producer_id'] = $postData['producer_id'];		//供应商
        	}
        	if($postData['type']  != null){
        		$where['po.type'] = $postData['type'];		//状态
        	}

					// $lists = $this->model->alias('po')
					// 	->field('po.*, p.proname,  d.name as depname')
					// 	->join(DB::getTable('Producer') . ' p', 'po.producer_id = p.id', 'LEFT')
					// 	->join(DB::getTable('Depot') . ' d', 'po.depot_id = d.id', 'LEFT')
					// 	->where('po.type','0')
					// 	->where($where)
	    //                 ->order($sort, $order)
	    //                 ->limit($offset, $limit)
					// 	->select();

			$list = db('Purchase_order')->alias('po')
					->field('po.id as poid,po.order_num, pr.proname, po.createtime, po.rk_type, po.cj_type, pt.num,pt.lotnum, pt.name as ptname, pe.name as pename , pt.unit, pt.sizes, pf.good_num, pf.cost, pf.totalcost,pf.totalprice, pt.price, pf.expirestime, pt.addr, po.remark')
					->join(DB::getTable('Purchase_flow') . ' pf', 'po.id = pf.purchase_id', 'LEFT')
					->join(DB::getTable('Product') . ' pt', 'pf.goods_id = pt.id', 'LEFT')
					->join(DB::getTable('Producer') . ' pr', 'po.producer_id = pr.id', 'LEFT')
					->join(DB::getTable('Depot') . ' d', 'po.depot_id = d.id', 'LEFT')
					->join(DB::getTable('Protype'). ' pe', 'pt.pdutype2_id = pe.id', 'LEFT')
					->where($where)
					->order('po.createtime',"DESC")
					->select();
					// dump(count($list));die;
					// var_dump($list);
			$data = [];
			$datas = [];
			$alls['num'] = '';
			$alls['cost'] = '';
			$alls['price'] = '';
			$counts = [];
			foreach ($list as $key => $v) {
				if(!isset($data[$v['poid']])){
			        $data[$v['poid']][]=$v;
			        
			    }else{
			        $data[$v['poid']][]=$v;
			    }
			}
			
			foreach ($data as $key => $va) {		
				foreach ($va as $ke => $val) {
					$alls['num'] += $val['good_num'];
					$alls['cost'] += $val['good_num']*$val['cost'];
					$alls['price'] += $val['good_num']*$val['price'];
					$all_num[$key][]= $val['good_num'];
					$all_totalcost[$key][]= $val['good_num']*$val['cost'];
					$all_totalprice[$key][]= $val['good_num']*$val['price'];
					foreach ($all_num as $keyy => $value) {
						$datas[$key]['all_num'] = array_sum($value);
						$datas[$key]['rk_type'] = $val['rk_type'];
						$datas[$key]['cj_type'] = $val['cj_type'];
					}
					foreach ($all_totalcost as $keyy => $value) {
						$datas[$key]['all_totalcost'] = array_sum($value);
					}
					foreach ($all_totalprice as $keyy => $value) {
						$datas[$key]['all_totalprice'] = array_sum($value);
					}
				}
				$counts[$key] = count($va);
			}
			
			// dump($counts);
			$this->view->assign('counts',$counts);
			$this->view->assign('data', $data);
			$this->view->assign('datas', $datas);	//自身单号下的合计
			$this->view->assign('alls', $alls);		//全部单号的合计
			
			$this->view->assign('where', json_encode($where));
            // return json($result);

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

        return $this->commondownloadprocess('stockdetail', 'Stockdetail name', $whereAddon);
    }


}
