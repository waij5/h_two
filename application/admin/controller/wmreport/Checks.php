<?php

namespace app\admin\controller\wmreport;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 仓库盘点
 *
 * @icon fa fa-circle-o
 */
class Checks extends Backend
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
            $list = db('project')->alias('p')
                    ->field('p.pro_id, p.pro_name, p.pro_spell, p.pro_code, p.pro_stock, p.pro_spec, p.pro_unit, p.depot_id, p.pro_cat1, p.pro_cat2, d.name as dname, u.name as uname')
                    ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->where('p.pro_stock','>','0')
                    ->where($where)
                    ->order('p.pro_id','DESC') 
                    ->select();
			foreach ($list as $key => $v) {
                $list[$key]['pro_cat1'] = isset($pdcatData[$v['pro_cat1']]) ? $pdcatData[$v['pro_cat1']] : '';
                $list[$key]['pro_cat2'] = isset($pdcatData[$v['pro_cat2']]) ? $pdcatData[$v['pro_cat2']] : '';
            }
			$this->view->assign('data', $list);
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

        return $this->commondownloadprocess('checks', 'Checks name', $whereAddon);
    }

}