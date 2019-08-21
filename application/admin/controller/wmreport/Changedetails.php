<?php

namespace app\admin\controller\wmreport;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Changedetails extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		
        $this->view->assign("depotList", $depotLists);
        $this->view->assign("typeC", model('Manifest')->getChangedetailsType());
    }



    public function index(){
    	$data = [];
    	$where = [];
    	if($this->request->isPost()){
    		$postData = $this->request->post();
    		if($postData['stime'] && $postData['etime']){
        		$censusDate = '查询日期：'.$postData['stime'].' 至 '.$postData['etime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['sl.sltime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
        	}
        	
        	if($postData['depot_id']){
        		$where['p.depot_id'] = $postData['depot_id'];
        	}
        	if($postData['p_num'] != null){
        		$where['p.pro_code'] = $postData['p_num'];
        	}
            if($postData['lot'] != null){
                $where['l.lotnum'] = $postData['lot'];
            }
        	if($postData['p_name']){
        		$where['p.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if(is_numeric($postData['typec'])){
        		$where['sl.smalltype'] = $postData['typec'];
        	}

    		$list = db('wm_stocklog')->alias('sl')
                    ->field('sl.*, l.lotnum,p.pro_spec, p.pro_name, p.pro_id, p.pro_code, d.dept_name, u.name as uname')
                    ->join('yjy_wm_lotnum l', 'sl.slotid = l.lot_id', 'LEFT')
                    ->join('yjy_project p', 'l.lpro_id = p.pro_id', 'LEFT')
                    ->join('yjy_deptment d', 'sl.sldepartment = d.dept_id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->where($where)
                    ->order('sl.sltime','ASC')
                    ->select();
            

            // var_dump($list);
            foreach ($list as $k => $v) {
            		$data[$v['pro_id']][] = $v;
            }
            $counts = [];
            foreach ($data as $key => $val) {
                $counts[$key] = count($val);
            }

            // var_dump($data);die();
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

        return $this->commondownloadprocess('changedetails', 'Changedetails name', $whereAddon);
    }



}