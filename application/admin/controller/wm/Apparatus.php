<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 医疗器械管理
 *
 * @icon fa fa-circle-o
 */
class Apparatus extends Backend
{
	/**
     * Apparatus型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Apparatus');

        $supplierList = db('wm_supplier')->where('sup_status','1')->select();
    	$this->view->assign('supplierList', $supplierList);

    	$depotList = db('depot')->where(['type'=>'3', 'status'=>'normal'])->select();
    	$this->view->assign('depotList', $depotList);
    	$userList = model('Admin')->order('username', 'asc')->select();
    	$this->view->assign('userList', $userList);

    	$unitList = [];
	
		
		$unitLists = model('Unit')->where('status','normal')->order('id', 'asc')->select();
		foreach ($unitLists as $k => $v)
        {
            $unitList[$v['name']] = $v;
        }
		
		
		$deptList = db('deptment')->where(['dept_status'=>'1'])->order('dept_id', 'asc')->select();
        $this->view->assign('deptList', $deptList);

        
        
        $this->view->assign("unitList", $unitList);
    }

    public function index(){
    	if ($this->request->isAjax()){

    		$filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                // $mapt['createtime'] = array("between",array($startr,$endr));
                $map['ap.a_createtime'] = array("between",array($startr,$endr));
                $maps['a_createtime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $maps= [];
            }

    		list($where, $sort, $order, $offset, $limit) = $this->buildparams();
    		$list = DB::table('yjy_wm_apparatus')->alias('ap')
    				->field('ap.*, u.name as u_name, depot.name as depot_name')
    				->join('yjy_unit u','ap.a_unit = u.id', 'LEFT')
                    ->join('yjy_depot depot', 'ap.a_depot = depot.id', 'LEFT')
    				->where($where)
    				->where($map)
    				->order('ap.a_id','DESC')
    				->limit($offset, $limit)
    				->select();

			$total = DB::table('yjy_wm_apparatus')->where($where)->where($maps)->count();

			$result = array("total" => $total, "rows" => $list);
            return json($result);
    	}
    	return $this->view->fetch();
    }

    public function add(){
    	if($this->model->select()){
            $num=$this->model->field('max(a_id) as id')->find();
            $nums = $num['id']+1;
            $a_code = 'YLQX'.$nums;
            $this->view->assign("a_code", $a_code);
        }else{
            $this->view->assign("a_code", 'YLQX1');
        }

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                // dump($params);die();
                $a_code = $params['a_code'];

                $onum_res = db('wm_apparatus')->where('a_code',$a_code)->find();
                if(empty($onum_res)){
                    $result = $this->model->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }

                }else{
                    // $this->error('编号已存在，请更换编号！');
                    $num=$this->model->field('max(a_id) as id')->find();
                    $nums = $num['id']+1;
                    $params['a_code'] = 'YLQX'.$nums;
                    // var_dump($params);die();
                    $result = $this->model->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }

                }


            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
       
        
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {                   
                $result = $row->save($params);
                if ($result !== false)
                {
                    $this->success();
                }
                else
                {
                    $this->error($row->getError());
                }
                
                
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
       
        // $this->view->assign('goods_id',$ids);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    public function index_al($ids = NULL){
    	if($this->request->isGet()){
        
            if (!$ids){
                $this->error(__('No Results were found'));

            }
            $this->view->assign('apId',$ids);
            // var_dump($where);
            $list = DB::table('yjy_wm_applotnum')->alias('al')
    				->field('al.*, ap.a_name,ap.a_join_stock, ap.a_out_stock,a.nickname, d.dept_name, sup.sup_name')
    				->join('yjy_wm_apparatus ap', 'al.al_aid=ap.a_id', 'LEFT')
                    ->join('yjy_admin a', 'al.aluser=a.id', 'LEFT')
                    ->join('yjy_deptment d', 'al.aldepart=d.dept_id','LEFT')
                    ->join('yjy_wm_supplier sup', 'al.alsupplier = sup.sup_id', 'LEFT')
                    ->where('ap.a_id',$ids)
                    ->order('al.aloperate_time','DESC')
                    ->select();


            // var_dump($depotData);var_dump($deptmentData);

            if($list){
            	$joinNum = $list[0]['a_join_stock'];
            	$outNum = $list[0]['a_out_stock'];
            	$this->view->assign('joinNum',$joinNum);
            	$this->view->assign('outNum',$outNum);
                $this->view->assign('list',$list);
            }
        }
        return $this->view->fetch();
    }


    public function add_al($ids = NULL){
        if($ids){
            $apData = db('wm_apparatus')->where('a_id',$ids)->find();
            $this->view->assign('apData',$apData);
        }       

        if ($this->request->isAjax())
        {
            $addData = [];
            $addData['al_aid'] = $this->request->post('al_aid');
            $addData['alotnum'] = $this->request->post('alotnum');
            $addData['alcost'] = $this->request->post('alcost');
            $addData['alnum'] = $this->request->post('alnum');
            $addData['alstatus'] = $this->request->post('alstatus');
            $addData['alsupplier'] = $this->request->post('alsupplier');
            $addData['aluser'] = $this->request->post('aluser');
            $addData['aldepart'] = $this->request->post('aldepart');
            $addData['alremark'] = $this->request->post('alremark');
            $addData['alproducer'] = $this->request->post('alproducer');
            $addData['alstime'] = strtotime($this->request->post('alstime'));
            $addData['aletime'] = strtotime($this->request->post('aletime'));
            $addData['alshop_time'] = strtotime($this->request->post('alshop_time'));
            $addData['aloperate_time'] = time();
            
            $intNum = intval($addData['alnum']);
            $floatNum = floatval($addData['alnum']);
            $floatmcost = floatval($addData['alcost']);

            if(!$addData['alotnum']){
                $this->error('批号必填！');
            }else if($addData['alcost']<0){
                $this->error('价格必填且大于0！');
            }else if(preg_match('/^[0-9]+(\.[0-9]{1,4})?$/', $floatmcost) ==0){
                $this->error('价格只能为小数点后两位的小数！');
            }else if($addData['alnum']<=0 || $floatNum <1 || $intNum != $floatNum){
                $this->error('数量必填且为大于0的整数！');
            }else{

                \think\Db::startTrans();                //开启db回滚;
                $res = ['error' => false, 'msg' => '1'];
                if($addData['alstatus']==1){
                    $addData['alusable_num'] = $addData['alnum'];
                    $alot_id = DB::table('yjy_wm_applotnum')->max('alot_id');
                    $addData['alot_id'] = $alot_id+1;
                    $alRes = DB::table('yjy_wm_applotnum')->insert($addData);
                    $apRes = DB::table('yjy_wm_apparatus')->where('a_id',$addData['al_aid'])->setInc('a_join_stock', $addData['alnum']);//自增
                    if(!$alRes || !$apRes){
                        $res = ['error' => true, 'msg' => '2'];
                    }

                }
                /*elseif ($addData['alstatus']==2) {

                    

                }*/

                if($res['error'] == false){
                    \think\Db::commit();
                    return json($res['msg']);
                }else{
                    \think\Db::rollback();
                    return json($res['msg']);
                }

            }
        }

        
        return $this->view->fetch();
    }


    public function scrap_al($ids = NULL){
        if($ids){
            $data = DB::table('yjy_wm_applotnum')->alias('al')
                    ->field('al.*, ap.a_name')
                    ->join('yjy_wm_apparatus ap', 'al.al_aid=ap.a_id', 'LEFT')
                    ->where(['al.al_aid'=>$ids,'al.alstatus'=>1])
                    ->order('al.aletime','ASC')
                    ->select();
            
        }else{
            $data =[];
        }

        if($this->request->isAjax()){
            if($this->request->post('type')==1){
                
                $alot_id = $this->request->post('alot_id');
                if($alot_id){
                    $alData = DB::table('yjy_wm_applotnum')->alias('al')
                            ->field('al.*, ap.a_name')
                            ->join('yjy_wm_apparatus ap', 'al.al_aid=ap.a_id', 'LEFT')
                            ->where(['al.alot_id'=>$alot_id,'al.alstatus'=>1])
                            ->find();

                    $alData['aletime'] = $alData['aletime']>0?date('Y-m-d',$alData['aletime']):'';
                    
                }else{
                    $alData =[];
                }
                return json($alData);
            }elseif ($this->request->post('type')==2) {
                $addData=[];
                $alot_id = $this->request->post('alot_id/a');
                $alnum = $this->request->post('alnum/a');
                foreach ($alnum as $key => $value) {
                    if($value =='' ){
                        $this->error('报废数量必填！');
                    }elseif (floatval($value) <1 || intval($value) != floatval($value)) {
                        $this->error('报废数量必须为整数！');
                    }
                }
                foreach ($alot_id as $k => $v) {
                    // $aga = $v;
                    $alData[$k] = DB::table('yjy_wm_applotnum')->where('alot_id',$v)->find();
                    if($alData[$k]['alusable_num']<intval($alnum[$k])){
                        $this->error('报废数量不能大于在库库存数！');
                    }else{
                        $alData[$k]['save_alnum'] = intval($alnum[$k]);
                    }

                }
                if($alData){

                    \think\Db::startTrans();                //开启db回滚;
                    $res = ['error' => false, 'msg' => '1'];

                    foreach ($alData as $ke => $v) {
                        $insertAlData[]=[
                            'al_aid'=>$v['al_aid'],
                            'alot_id'=>$v['alot_id'],
                            'alotnum'=>$v['alotnum'],
                            'alsupplier'=>$v['alsupplier'],
                            'alnum'=>$v['save_alnum'],
                            'alcost'=>$v['alcost'],
                            'alproducer'=>$v['alproducer'],
                            'aluser'=>$v['aluser'],
                            'aldepart'=>$v['aldepart'],
                            'alstatus'=>2,
                            'alstime'=>$v['alstime'],
                            'aletime'=>$v['aletime'],
                            'alshop_time'=>$v['alshop_time'],
                            'aloperate_time'=>time(),
                        ];

                    }
                    if($insertAlData){
                        $alAddRes = db('wm_applotnum')->insertAll($insertAlData);
                        foreach ($insertAlData as $k => $val) {
                            $apOutRes = DB::table('yjy_wm_apparatus')->where('a_id',$val['al_aid'])->setInc('a_out_stock', $val['alnum']);
                            $apJoinRes = DB::table('yjy_wm_apparatus')->where('a_id',$val['al_aid'])->setDec('a_join_stock', $val['alnum']);//自减setDec
                            $alAlusableRes = DB::table('yjy_wm_applotnum')->where(['alot_id'=>$val['alot_id'],'alstatus'=>1])->setDec('alusable_num', $val['alnum']);
                        }
                        if(!$alAddRes || !$apOutRes || !$apJoinRes || !$alAlusableRes){
                            $res = ['error' => true, 'msg' => '2'];
                        }
                    }
                    // return $insertAlData;

                    if($res['error'] == false){
                        \think\Db::commit();
                        return json($res['msg']);
                    }else{
                        \think\Db::rollback();
                        return json($res['msg']);
                    }
                }
                
            }
        }
        $this->view->assign('data',$data);
        return $this->view->fetch();
    }


    public function edit_al($ids = NULL)
    {
        $row = DB::table('yjy_wm_applotnum')->alias('al')
                ->field('al.*,ap.a_name')
                ->join('yjy_wm_apparatus ap', 'al.al_aid=ap.a_id', 'LEFT')
                ->find($ids);
        $row['alstime'] = $row['alstime']>0?date('Y-m-d',$row['alstime']):'';
        $row['aletime'] = $row['aletime']>0?date('Y-m-d',$row['aletime']):'';
        $row['alshop_time'] = $row['alshop_time']>0?date('Y-m-d',$row['alshop_time']):'';
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {                   
                $result = $row->save($params);
                if ($result !== false)
                {
                    $this->success();
                }
                else
                {
                    $this->error($row->getError());
                }
                
                
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
       
        // $this->view->assign('goods_id',$ids);
        $this->view->assign("alData", $row);
        return $this->view->fetch();
    }

}