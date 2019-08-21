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
class Depdraw extends Backend
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
        
        $userLists = model('Admin')->order('id', 'asc')->select();
        
        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
        
        $this->view->assign("deptList", $deptLists);
        $this->view->assign("userList", $userLists);
        $this->view->assign("depotList", $depotLists);
        
    }

    public function index(){
    	$where = [];
    	$data = [];

    	if($this->request->isPost()){

        	$postData = $this->request->post();
        	if($postData['stime'] && $postData['etime']){
        		$censusDate = '统计日期：'.$postData['stime'].' 至 '.$postData['etime'];
        		$this->view->assign('censusDate', $censusDate);
        		$where['mf.mcreatetime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];	//入库日期."00:00".":00"."23:59".":59" 
        	}
        	if($postData['lotnum'] != null){
        		$where['l.lotnum'] = $postData['lotnum'];		//批号
        	}
        	if($postData['p_name']){
        		$where['p.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
        	}
        	if($postData['order_num']){
        		$where['mf.man_num'] = $postData['order_num'];//领料单号
        	}
        	if($postData['dept'] !=null){
        		$where['mf.mdepart_id'] = $postData['dept'];//领料科室
        	}
            if($postData['depot_id'] !=null){
                $where['mf.mdepot_id'] = $postData['depot_id'];//所属仓库
            }
        	if($postData['out_id'] !=null){
        		$where['mf.mout_id'] = $postData['out_id'];//领料人
        	}
        	if($postData['type'] !=null && $postData['type']!=99){
        		$where['mf.mprimary_type'] = $postData['type'];//领料、领药
        	}elseif ($postData['type']==99) {
                // echo '9898944-46454';
                $where['mf.mprimary_type'] = array('in',array(4,5));
            }

        	$countData = db('wm_manifest')->alias('mf')
                        ->field("sum(ml.mallcost) as 'mallcost', sum(ml.mpro_num) as 'mallmpro_num'")
                        ->join('yjy_wm_manlist ml', 'mf.man_id = ml.manid', 'LEFT')
                        ->join('yjy_wm_lotnum l', 'ml.lotid = l.lot_id', 'LEFT')
                        ->join('yjy_project p', 'l.lpro_id = p.pro_id', 'LEFT')
                        ->where($where)
                        ->select();

            $pdcatData = db('pducat')->where(['pdc_zpttype'=> '4'])->column('pdc_id,pdc_name');
            $list = db('wm_manifest')->alias('mf')
                    ->field('mf.*, l.lotnum, ml.mallcost, ml.mpro_num, p.pro_name, p.pro_code, p.pro_spec, p.pro_unit, p.pro_cat1, p.pro_cat2, d.dept_name, c.ctm_name, a.nickname, dt.name as dtname, u.name as uname')
                    ->join('yjy_wm_manlist ml', 'mf.man_id = ml.manid', 'LEFT')
                    ->join('yjy_wm_lotnum l', 'ml.lotid = l.lot_id', 'LEFT')
                    ->join('yjy_project p', 'l.lpro_id = p.pro_id', 'LEFT')
                    ->join('yjy_deptment d', 'mf.mdepart_id = d.dept_id', 'LEFT')
                    ->join('yjy_customer c', 'mf.member_id = c.ctm_id', 'LEFT')
                    ->join('yjy_admin a', 'mf.mout_id = a.id', 'LEFT')
                    ->join('yjy_depot dt', 'mf.mdepot_id = dt.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->where($where)
                    ->order('mf.mcreatetime', 'DESC')->order('mf.man_num','DESC')
                    ->select();
        			
			

            $data = [];
            foreach ($list as $key => $v) {
                $list[$key]['pro_cat1'] = isset($pdcatData[$v['pro_cat1']]) ? $pdcatData[$v['pro_cat1']] : '';
                $list[$key]['pro_cat2'] = isset($pdcatData[$v['pro_cat2']]) ? $pdcatData[$v['pro_cat2']] : '';
            }
			foreach ($list as $k => $v) {
				$data[$v['mdepart_id']][] = $v;
			}
// var_dump($data);die();
			$datas = [];
			$counts = [];
			foreach ($data as $k => $v) {
                foreach ($v as $ke => $va) {
					$all_num[$k][]= $va['mpro_num'];
					$all_money[$k][]= $va['mallcost'];
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
			$this->view->assign('alls', $countData[0]);		//全部科室的合计

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

        return $this->commondownloadprocess('depdraw', 'DepdrawName', $whereAddon);
    }


}