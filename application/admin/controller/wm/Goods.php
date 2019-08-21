<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;
use app\admin\model\Pdutype;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 产品登记
 *
 * @icon fa fa-circle-o
 */
class Goods extends Backend
{
    
    /**
     * Product模型对象
     */
    
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Drugs');
		$unitList = [];
		$deptmentList = [];
		$depotList = [];
        $subject = [];
		// 查询数据集
		
		$unitLists = model('Unit')->where('status','normal')->order('id', 'asc')->select();
		foreach ($unitLists as $k => $v)
        {
            $unitList[$v['name']] = $v;
        }
		
		$depotLists = model('Depot')->where('type','2')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['name']] = $v;
        }
		$deptmentLists = model('Deptment')->where('dept_status','1')->order('dept_id', 'asc')->select();
		foreach ($deptmentLists as $k => $v)
        {
            $deptmentList[$v['dept_name']] = $v;
        }

        $subjectLists = model('Pducat')->where(['pdc_zpttype'=> '4','pdc_status'=> '1','pdc_pid' => '0'])->select();
        foreach ($subjectLists as $k => $v)
        {
            $subject[$v['pdc_name']] = $v;
        }
        
        $proFeeType = model('Fee')->getList();
        $this->view->assign('proFeeType', $proFeeType);
        
        $this->view->assign("ptypeList", $this->model->getDrugTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("unitList", $unitList);
        $this->view->assign("depotList", $depotList);
        $this->view->assign("deptmentList", $deptmentList);
        $this->view->assign("subject", $subject);
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            
            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                // $mapt['createtime'] = array("between",array($startr,$endr));
                $map['pro.createtime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
            }
            // var_dump($filter['stime']);die();

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            
            $total = $this->model->alias('pro')
                    ->where($map)->where($where)
					->where('pro.pro_type','2')        //2.物品
                    // ->order($sort, $order)
                    ->count();

            $list = $this->model->alias('pro')
                    ->field('pro.*, u.name as u_name, pd.pdc_name, pdc.pdc_name as pro_cat, depot.name as depot_name')
                    ->join('yjy_unit u','pro.pro_unit = u.id', 'LEFT')
                    ->join('yjy_pducat pd', 'pro.pro_cat1 = pd.pdc_id', 'LEFT')
                    ->join('yjy_pducat pdc', 'pro.pro_cat2 = pdc.pdc_id', 'LEFT')
                    ->join('yjy_depot depot', 'pro.depot_id = depot.id', 'LEFT')
                    ->where($map)->where($where)
					->where('pro.pro_type','2')
                    ->order(['pro.pro_id' => 'desc', 'pro.pro_status' => 'desc'])
                    // ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
                    
                    
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

     /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        
        return $this->commondownloadprocess('GoodsReport', 'All business goods statistic');
    }


    // <!-- 2017-09-28  子非魚 -->
    public function add(){
        if($this->model->select()){
            $num=$this->model->field('max(pro_id) as id')->find();
            $nums = $num['id']+1;
            $wp_num = 'wp'.$nums;
            $this->view->assign("wp_num", $wp_num);
        }else{
            $this->view->assign("wp_num", 'wp1');
        }

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                // dump($params);die();
                $pro_code = $params['pro_code'];
                $pro_cost = $params['pro_cost'];
                $pro_amount = $params['pro_amount'];
                if($pro_cost<0 || $pro_amount<0){
                    $this->error('成本或售价不得小于0！');
                }

                $onum_res = db('Project')->where('pro_code',$pro_code)->find();
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
                    $num=$this->model->field('max(pro_id) as id')->find();
                    $nums = $num['id']+1;
                    $params['pro_code'] = 'wp'.$nums;
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

    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(pro_id) as id')->find();
                $numss = $nums['id']+1;
                $wp_num = 'wp'.$numss;
                // var_dump($yp_num);
                return json($wp_num);
            }

        }
    }


    public function ajaxSubject(){
        if ($this->request->isAjax()){
            $sid = $this->request->post('subject_id');
            $str = "<option value=''>请选择类别</option>";
            if(!empty($sid) && $sid != 0){
                $data = model('Pducat')->where('pdc_pid', $sid)->where('pdc_status','1')->column('pdc_id,pdc_pid,pdc_name');
                if($data){
                    foreach ($data as $key => $v) {
                        $str.= "<option value=".$v['pdc_id'].">".$v['pdc_name']."</option>";
                    }
                }else{
                    $str = '<option value="">无二级类别</option>';
                }

            }else{
                $str = '<option value=""></option>';
            }
            return $str;
        }
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
                $cost = $params['pro_cost'];
                $price = $params['pro_amount'];
                if($cost<0 || $price<0){
                    $this->error('成本或售价不得小于0！');
                }

                
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
        
        $aa = model('Drugs')->where('pro_id', $ids)->column('pro_cat1,pro_cat2');
        $lockType = 0;//判断当前产品是否有过库存，锁定仓库、单位、规格的修改
        $lockType = db('wm_lotnum')
                    ->where('lpro_id', $ids)
                    ->count();
        $pro_cat2 = '';
        if(!empty($aa)){
            foreach ($aa as $key => $value) {
                $pData = model('Pducat')->where('pdc_pid', $key)->column('pdc_id,pdc_pid,pdc_name');
                foreach ($pData as $key => $v) {
                    if($value == $v['pdc_id']){
                        $select = '  selected';
                    }else{
                        $select = '';
                    }
                    $pro_cat2.= "<option value='".$v['pdc_id']."'".$select.">".$v['pdc_name']."</option>";
                }
            }
            
        }else{
            $pro_cat2 = '<option value=""></option>';
        }
        
        // var_dump($pdutype2_id);
        $this->view->assign('pro_cat2',$pro_cat2);
        $this->view->assign('goods_id',$ids);
        $this->view->assign("row", $row);
        $this->view->assign("lockType", $lockType);
        return $this->view->fetch();
    }

    public function searchLot($ids = NULL){
        
        if($ids){
            $row = db('wm_lotnum')->where('lpro_id',$ids)->select();

        }else{
            $this->error(__('No Results were found'));
        }
        
        $this->view->assign('row',$row);
        return $this->view->fetch();
    }
    

    // <!-- 2017-09-28  子非魚 -->


    //    改价
    public function changecost(){
        $postData = $this->request->post();
        if($postData){
            $lot_id = $postData['lot_ids'];
            $lcost = $postData['lcosts'];
            $lprice = $postData['lprices'];

            \think\Db::startTrans();                //开启db回滚;
            $res = ['error' => false, 'msg' => '1'];     //设置回滚状态及信息 msg[1 => '成功'，2 => '无传值'，3 => '保存失败']

            $manListData = db('wm_manlist')->field('ml_id,lotid,mpro_num')->where('lotid',$lot_id)->select();
            $stockLogData = db('wm_stocklog')->field('sl_id,slotid,slnum')->where('slotid',$lot_id)->select();

            if($manListData && $stockLogData){
                foreach ($manListData as $k => $v) {
                    $manListData[$k]['mallcost'] = $v['mpro_num']*$lcost;
                    $manListData[$k]['mallprice'] = $v['mpro_num']*$lprice;
                }
                foreach ($stockLogData as $k => $v) {
                    $stockLogData[$k]['slallcost'] = $v['slnum']*$lcost;
                    $stockLogData[$k]['slallprice'] = $v['slnum']*$lprice;
                }

                $lotnumRes = db('wm_lotnum')->where('lot_id',$lot_id)->update(['lcost' => $lcost, 'lprice' => $lprice, 'lchangecost_type' => '1']);
                if(!$lotnumRes){
                    $res = ['error' => true, 'msg' => '3'];
                    break;
                }

                foreach ($manListData as $ke => $va) {
                    $manListRes = db('wm_manlist')->where('ml_id',$va['ml_id'])->update(['mcost' => $lcost, 'mprice' => $lprice, 'mallcost' => $va['mallcost'], 'mallprice' => $va['mallprice']]);
                }
                if(!$manListRes){
                    $res = ['error' => true, 'msg' => '3'];
                    break;
                }
                foreach ($stockLogData as $ke => $va) {
                    $stockLogRes = db('wm_stocklog')->where('sl_id',$va['sl_id'])->update(['slcost' => $lcost, 'slprice' => $lprice, 'slallcost' => $va['slallcost'], 'slallprice' => $va['slallprice']]);
                }
                if(!$stockLogData){
                    $res = ['error' => true, 'msg' => '3'];
                    break;
                }

            }else{      
                $res = ['error' => true, 'msg' => '3'];
                break;
            }
            

            if($res['error'] == false){
                \think\Db::commit();
                return $res['msg'];
                // var_dump($stockLogData);
                // $this->success();
            }else{
                \think\Db::rollback();
                return $res['msg'];
            }

        }else{
            return '2';     //传值无数据，失败
        }
        
        // return $postData;
    }


    //修改有效日期
    public function changeletime(){
        $postData = $this->request->post();
        if($postData){
            \think\Db::startTrans();                //开启db回滚;
            $res = ['error' => false, 'msg' => '1'];     //设置回滚状态及信息 msg[1 => '成功'，2 => '无传值'，3 => '保存失败']
            $checkLotid = DB::table('yjy_wm_lotnum')->where('lot_id',$postData['lot_id'])->find();
            if($checkLotid){
                $letime = strtotime($postData['letime']);
                $lotnumRes = DB::table('yjy_wm_lotnum')->where('lot_id',$postData['lot_id'])->update(['letime'=>$letime]);
                $manlistRes = DB::table('yjy_wm_manlist')->where('lotid',$postData['lot_id'])->update(['metime'=>$letime]);
                if(!$lotnumRes || !$manlistRes){
                    $res = ['error' => true, 'msg' => '3'];
                    break;
                }
            }else{
                return '2';     //传值无数据，失败
            }

            if($res['error'] == false){
                \think\Db::commit();
                return $res['msg'];
                // var_dump($stockLogData);
                // $this->success();
            }else{
                \think\Db::rollback();
                return $res['msg'];
            }

        }else{
            return '2';     //传值无数据，失败
        }
    }

    
    public function delGoods(){
        $pro_id = $this->request->post('pro_id');
        $checkShowDel = 0;//判断当前产品是否有过库存，锁定仓库、单位、规格的修改
        if($pro_id){
            $checkShowDel = db('wm_lotnum')
                    ->where('lpro_id', $pro_id)
                    ->count();
        }

        if($checkShowDel>0){
            return '1';//已有进库记录，不可删除
        }elseif($checkShowDel==0){
            $delRes = $this->model->where('pro_id',$pro_id)->delete();
            if($delRes){
                return '2';//删除成功
            }else{
                return '3';//删除失败
            }
            
        }

        
        // return $checkShowDel;
    }
    

}
