<?php

namespace app\admin\controller\wmreport;

use app\common\controller\Backend;

use think\Controller;
use think\Model;
use think\Request;
use think\DB;

/**
 * 仓库盘点
 *
 * @icon fa fa-circle-o
 */
class Psi extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
		$supplier = db('wm_supplier')->where('sup_status','1')->order('sup_type', 'asc')->select();
        $depotList = db('depot')->where('status','normal')->order('type', 'asc')->select();
        $this->view->assign("typeList", model('Manifest')->getPsiType());
        $this->view->assign("depotList", $depotList);
        $this->view->assign("supplier", $supplier);
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
        		$where['mf.mcreatetime'] = ['between',[strtotime($postData['sintime'].'00:00:00'),strtotime($postData['eintime'].'23:59:59')]];	//入库日期."00:00".":00"."23:59".":59" 
        	}
        	if($postData['p_num'] != null){
        		$where['pro.pro_code'] = $postData['p_num'];		//产品编号
        	}
        	if($postData['p_name']){
        		$where['pro.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if($postData['lotnum'] != null){
        		$where['lot.lotnum'] = $postData['lotnum'];		//批号
        	}
        	if($postData['depot_id'] != null){
        		$where['pro.depot_id'] = $postData['depot_id'];		//仓库
        	}
        	
        	if($postData['order_num'] != null){
        		$where['mf.man_num'] = $postData['order_num'];		//单号
        	}
        	if($postData['supplier_id'] != null){
        		$where['mf.msupplier_id'] = $postData['supplier_id'];		//供应商
        	}
        	if($postData['type']  != null){
        		$where['mf.mprimary_type'] = $postData['type'];		//状态
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
        	$countData = db('wm_manifest')->alias('mf')
        				->field("sum(ml.mallcost) as 'mallcost', sum(ml.mallprice) as 'mallprice'")
						->join('yjy_wm_manlist ml', 'mf.man_id = ml.manid', 'LEFT')
						->join('yjy_wm_lotnum lot', 'ml.lotid = lot.lot_id', 'LEFT')
						->join('yjy_project pro', 'lot.lpro_id = pro.pro_id', 'LEFT')
						->join('yjy_wm_supplier s', 'mf.msupplier_id = s.sup_id', 'LEFT')
						->where($where)
						// ->sum('ml.mallcost'+'ml.mallprice')
						->select();
						// var_dump($countData);die;

			$list = db('wm_manifest')->alias('mf')
					->field('mf.man_id, mf.msecond_type, mf.man_num, mf.mcreatetime, mf.mremark, ml.mpro_num, ml.mcost, ml.mallcost, ml.mprice, ml.mallprice, ml.metime, lot.lotnum, lot.lproducer,s.sup_name,pro.pro_code, pro.pro_name, pro.pro_unit, pro.pro_spec, u.name as uname')
					->join('yjy_wm_manlist ml', 'mf.man_id = ml.manid', 'LEFT')
					->join('yjy_wm_lotnum lot', 'ml.lotid = lot.lot_id', 'LEFT')
					->join('yjy_project pro', 'lot.lpro_id = pro.pro_id', 'LEFT')
					->join('yjy_wm_supplier s', 'mf.msupplier_id = s.sup_id', 'LEFT')
					->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
					->where($where)
					->order('mf.mcreatetime',"DESC")
					->select();
					// dump(count($list));die;
					// var_dump($list);die;
			$data = [];
			$datas = [];
			$alls['num'] = '';
			$alls['cost'] = '';
			$alls['price'] = '';
			$counts = [];
			foreach ($list as $key => $v) {
		        $data[$v['man_id']][]=$v;
			}
			
			foreach ($data as $key => $va) {		
				foreach ($va as $ke => $val) {
					$all_num[$key][]= $val['mpro_num'];
					$all_totalcost[$key][]= $val['mallcost'];
					$all_totalprice[$key][]= $val['mallprice'];
					foreach ($all_num as $keyy => $value) {
						$datas[$key]['all_num'] = array_sum($value);
						$datas[$key]['type'] = $val['msecond_type'];
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
			// var_dump($datas);die;
			// dump($counts);
			$this->view->assign('counts',$counts);
			$this->view->assign('data', $data);
			$this->view->assign('datas', $datas);	//自身单号下的合计
			$this->view->assign('alls', $countData[0]);		//全部单号的合计
			
			$this->view->assign('where', json_encode($where));
            // return json($result);

        }
        
        return $this->view->fetch();
    }

    //加载更多
    public function psiAjax(){
    	
    }

     /**
     * 获取进度信息
     */
    public function downloadprocess()
    {

        $whereAddon = input('yjyWhere', '[]');
        $whereAddon = json_decode($whereAddon, true);

        return $this->commondownloadprocess('psi', 'Psi name', $whereAddon);
    }


}
