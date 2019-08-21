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
class Check extends Backend
{
	protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        // $this->model = model('PurchaseOrder');
		$depotList = [];
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $this->view->assign("depotList", $depotList);
    }


	public function index(){

		if($this->request->isPost()){

        	$postData = $this->request->post();
        	$where = [];
        	if($postData['c_num'] != null){
        		$where['pt.num'] = $postData['c_num'];		//产品编号
        	}
        	
        	if($postData['c_name'] != null){
        		$where['pt.name'] = array('like','%'.$postData['c_name'].'%');
        	}
        	if($postData['c_lotnum'] != null){
        		$where['pt.lotnum'] = $postData['c_lotnum'];
        	}
        	if($postData['c_depot_id'] != null){
        		$where['pt.depot_id'] = $postData['c_depot_id'];		//仓库
        	}

        	$counts = db('Product')->alias('pt')->where('pt.stock','>','0')->where($where)->count();
        	$list = db('Product')->alias('pt')
        			->field('pt.id, pt.code, pt.name, pt.num, pt.lotnum, pt.stock, pt.sizes, pt.unit, pt.depot_id, d.name as dname')
        			->join(DB::getTable('Depot') . ' d', 'pt.depot_id = d.id', 'LEFT')
        			->where('pt.stock','>','0')
        			->where($where)
        			->order('pt.id','DESC')
        			->select();

			$this->view->assign('counts',$counts);
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

        return $this->commondownloadprocess('check', 'Check name', $whereAddon);
    }

}