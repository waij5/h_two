<?php

namespace app\admin\controller\wmreport;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 批次盘点
 *
 * @icon fa fa-circle-o
 */
class Checklot extends Backend
{
	protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		
        $this->view->assign("depotList", $depotLists);
    }


	public function index(){

		if($this->request->isPost()){

        	$postData = $this->request->post();
        	$where = [];
        	if($postData['c_num'] != null){
        		$where['p.pro_code'] = $postData['c_num'];		//产品编号
        	}
        	
        	if($postData['c_name'] != null){
        		$where['p.pro_name'] = array('like','%'.$postData['c_name'].'%');
        	}
        	
        	if($postData['c_depot_id'] != null){
        		$where['p.depot_id'] = $postData['c_depot_id'];		//仓库
        	}

        	
        
            $pdcatData = db('pducat')->where(['pdc_zpttype'=> '4'])->column('pdc_id,pdc_name');
            $lists = db('project')->alias('p')
                    ->field('p.pro_id, p.pro_name, p.pro_code,  p.pro_spec, p.depot_id, d.name as dname, p.pro_cat1, p.pro_cat2, u.name as uname, lot.lot_id,lot.lotnum,lot.lstock,lot.lcost,lot.letime,lot.lproducer,sup.sup_name')
                    ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_wm_lotnum lot', 'p.pro_id = lot.lpro_id', 'LEFT')
                    ->join('yjy_wm_manlist ml', 'lot.lot_id = ml.lotid', 'LEFT')
                    ->join('yjy_wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
                    ->join('yjy_wm_supplier sup', 'mf.msupplier_id = sup.sup_id', 'LEFT')
                    ->whereIn('mf.mprimary_type',['1','2'],'or')
                    ->where('lot.lstock','>','0')
                    ->where($where)
                    ->order('lot.lot_id','ASC')
                    ->select();
            $list = [];
            $counts = [];
            $total['stock'] = 0;
            $total['cost'] = 0;
            foreach ($lists as $key => $v) {
                $lists[$key]['pro_cat1'] = isset($pdcatData[$v['pro_cat1']]) ? $pdcatData[$v['pro_cat1']] : '';
                $lists[$key]['pro_cat2'] = isset($pdcatData[$v['pro_cat2']]) ? $pdcatData[$v['pro_cat2']] : '';
            }
            foreach ($lists as $k => $v) {
                $list[$v['pro_id']][] = $v;
            }
            foreach ($list as $ke => $va) {

                $counts[$ke] = count($va);
                foreach ($va as $key => $value) {
                    $total['stock'] += $value['lstock'];
                    $total['cost'] += $value['lcost']*$value['lstock'];
                }
                
            }
			// var_dump($total);
			$this->view->assign('data', $list);
            $this->view->assign('total', $total);
            $this->view->assign('counts', $counts);
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

        return $this->commondownloadprocess('checklot', 'Checklot name', $whereAddon);
    }

}