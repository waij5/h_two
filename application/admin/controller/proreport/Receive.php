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
class Receive extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
    	$deptList = [];
    	$userList = [];
        $depotLists = [];
        parent::_initialize();
        $deptLists = model('Deptment')->order('dept_id', 'asc')->select();
        foreach ($deptLists as $k => $v)
        {
            $deptList[$v['dept_id']] = $v;
        }
        $userLists = model('Admin')->order('id', 'asc')->select();
        foreach ($userLists as $k => $v)
        {
            $userList[$v['id']] = $v;
        }
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
        foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $this->view->assign("deptList", $deptList);
        $this->view->assign("userList", $userList);
        $this->view->assign("depotList", $depotList);
        
    }

    public function index(){
    	$where = [];
    	$data = [];

    	if($this->request->isPost()){

        	$postData = $this->request->post();
        	if($postData['stime'] && $postData['etime']){
        		$censusDate = '统计日期：'.$postData['stime'].' 至 '.$postData['etime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['do.createtime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];	//入库日期."00:00".":00"."23:59".":59" 
        	}
        	if($postData['lotnum'] != null){
        		$where['pt.lotnum'] = $postData['lotnum'];		//产品编号
        	}
        	if($postData['p_name']){
        		$where['pt.name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if($postData['order_num']){
        		$where['do.order_num'] = $postData['order_num'];//领料单号
        	}
        	if($postData['dept'] !=null){
        		$where['do.depart_id'] = $postData['dept'];//领料科室
        	}
            if($postData['depot_id'] !=null){
                $where['do.depot_id'] = $postData['depot_id'];//所属仓库
            }
        	if($postData['out_id'] !=null){
        		$where['do.out_id'] = $postData['out_id'];//领料科室
        	}
        	if($postData['type'] !=null){
        		$where['do.type'] = $postData['type'];//领料、领药
        	}

        	$list = db('depot_outks')->alias('do')
        			->field('do.*, dp.depotout_id, dp.goods_id, dp.goods_num, pt.name,pt.lotnum, pt.sizes, pt.unit, pt.cost, d.dept_name, pt.pdutype_id, pt.pdutype2_id, c.ctm_name, a.nickname, dt.name as depot_name')
        			->join(DB::getTable('Depart_product') . ' dp', 'do.id = dp.depotout_id', 'LEFT')
        			->join(DB::getTable('Product') . ' pt', 'dp.goods_id = pt.id', 'LEFT')
        			->join(DB::getTable('Deptment') . ' d','do.depart_id = d.dept_id', 'LEFT')
        			->join(DB::getTable('Customer') . ' c','do.member_id = c.ctm_id', 'LEFT')
        			->join(DB::getTable('Admin') . ' a','do.out_id = a.id', 'LEFT')
                    ->join(DB::getTable('Depot'). ' dt','do.depot_id = dt.id', 'LEFT')
        			->where($where)->order('do.createtime','DESC')
        			->order('do.order_num','DESC')
        			->select();
        			
			$list = model('Product')->getchineseName($list);

			foreach ($list as $k => $v) {
				$list[$k]['allmoney'] = $v['goods_num']*$v['cost'];
			}
			foreach ($list as $k => $v) {
				
				if(!isset($data[$v['depart_id']])){
					$data[$v['depart_id']][] = $v;
				}else{
					$data[$v['depart_id']][] = $v;
				}
			}
			$alls['nums'] = '';
			$alls['moneys'] = '';
			$datas = [];
			$counts = [];
			foreach ($data as $k => $v) {
                foreach ($v as $ke => $va) {
                    $alls['nums'] += $va['goods_num'];
					$alls['moneys'] += $va['allmoney'];
					$all_num[$k][]= $va['goods_num'];
					$all_money[$k][]= $va['allmoney'];
					foreach ($all_num as $key => $value) {
						$datas[$k]['all_num'] = array_sum($value);
					}
					foreach ($all_money as $key => $value) {
						$datas[$k]['all_money'] = array_sum($value);
					}
                }
                $counts[$k] = count($v);
            }
			// dump($counts);
			$this->view->assign('counts',$counts);
			$this->view->assign('data', $data);
			$this->view->assign('datas', $datas);	//自身科室下的合计
			$this->view->assign('alls', $alls);		//全部科室的合计

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

        return $this->commondownloadprocess('receive', 'ReceiveName', $whereAddon);
    }


}