<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 处方发药
 *
 * @icon fa fa-circle-o
 */
class Drugscflist extends Backend
{
    
    /**
     * DepotOutks模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotList = DB::table('yjy_depot')->where(['type'=>'1', 'status'=>'normal'])->select();
        $this->view->assign('depotList', $depotList);

        $deptmentList = DB::table('yjy_deptment')->where(['dept_status'=>'1'])->select();
        $this->view->assign('deptmentList', $deptmentList);
    }

    // 处方单列表
    public function index(){
        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // $whereData = $where;
            $list = DB::table('yjy_order_items')->alias('oi')
                ->field('oi.item_id,oi.customer_id,oi.pro_name,dr.order_item_id,dr.deduct_times,dr.status,c.ctm_name,dr.createtime,max(dr.createtime) as drtime,max(dr.order_item_id) as dr_oid')
                ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                ->where($where)
                ->where('dr.status', 'in',[1,2])
                ->where('oi.item_type', '1')
                // ->order($sort, $order)
                // ->order('dr.status','ASC')
                ->order('drtime','DESC')
                ->limit($offset, $limit)
                ->group('oi.customer_id')
                ->select();
            $total = DB::table('yjy_order_items')->alias('oi')
                ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                ->where($where)
                ->where('dr.status', 'in',[1,2])
                ->where('oi.item_type', '1')
                ->group('oi.customer_id')
                ->count();

            foreach ($list as $k => $v) {
                //根据开单人获取最新开单科室
                $list[$k]['prescriber'] = DB::table('yjy_order_items')->alias('oi')
                                // ->field('a.nickname')
                                ->join('yjy_admin a', 'oi.prescriber=a.id','LEFT')
                                ->join('yjy_deptment d', 'a.dept_id=d.dept_id','LEFT')
                                ->where('item_id',$v['dr_oid'])
                                ->value('d.dept_name');
                //是否发药，iffy=0表示无待发药的划扣；iffy>0表示有有待发药的划扣。
                $list[$k]['iffy']=DB::table('yjy_order_items')->alias('oi')
                        ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                        ->where('oi.customer_id',$v['customer_id'])
                        ->where('oi.item_type', '1')
                        ->where('dr.status','1')
                        ->count();


            }
            // $lists = sort($list);

            // array_multisort($tmp,SORT_DESC,$list);
            
            // $deptData = db('admin')->alias('a')->where('id','in',)
            // $total = count($list);
            
            // dump($whereData);
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
            return $this->view->fetch();
    }




    public function edit($ids = NULL,$depot_id = NULL,$dept_id = NULL){
        if($this->request->isGet()){
        
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $where=[];
            if($depot_id){
                $where['pro.depot_id'] = $depot_id;
            }
            if($dept_id){
                $where['a.dept_id'] = $dept_id;
            }
            // var_dump($where);
            $list = DB::table('yjy_order_items')->alias('oi')
                    ->field('oi.item_id,oi.customer_id,oi.pro_name,oi.item_total_times,dr.id as drid,dr.order_item_id,dr.deduct_times,dr.status,c.ctm_name,dr.createtime,d.dept_id,d.dept_name,depot.id as depotid,depot.name as depot_name')
                    ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                    ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                    ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                    ->join('yjy_deptment d', 'a.dept_id=d.dept_id','LEFT')
                    ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                    ->join('yjy_depot depot', 'pro.depot_id=depot.id','LEFT')
                    ->where('oi.customer_id',$ids)
                    ->where('dr.status', 'in',[1,2])
                    ->where('oi.item_type', '1')
                    ->where($where)
                    ->order('dr.id','DESC')
                    // ->order('dr.createtime','DESC')
                    ->select();

            $depotData = [];
            $deptmentData = [];
            

            // var_dump($depotData);var_dump($deptmentData);

            if($list){
                foreach ($list as $k => $v) {
                    $depotData[$v['depotid']] =$v['depot_name'];
                    $deptmentData[$v['dept_id']] =$v['dept_name'];
                }

                $this->view->assign('depotData',$depotData);
                $this->view->assign('deptmentData',$deptmentData);
                $this->view->assign('list',$list);
            }
        }
        return $this->view->fetch();
    }


    public function cf_print($ids=NULL,$depot_id = NULL,$dept_id = NULL,$printType=0){
    //$printType=0 为处方列表的打印；$printType=1 为所有客户的打印。
        if($this->request->isGet() && $printType==0){
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $ids = explode(',', $ids);
            /*$data = DB::table('yjy_wm_recipe')->alias('r')
                    ->field('r.re_id,sl.sldr_id, sl.slcustomer_id, sl.slnum, sl.slotid, d.name as dname,p.pro_name, p.pro_spec, lot.lotnum, u.name as uname')
                    ->join('yjy_wm_stocklog sl', 'r.rsl_id = sl.sl_id', 'LEFT')
                    ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                    ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
                    ->where('r.rdr_id', 'in',$ids)
                    ->order('r.rdr_id', 'DESC')
                    ->select();*/
            $data = DB::table('yjy_deduct_records')->alias('dr')
                    ->field('dr.id as drid,p.pro_name,p.pro_spec,dr.deduct_times,d.name as dname,u.name as uname')
                    ->join('yjy_order_items oi','dr.order_item_id=oi.item_id','LEFT')
                    ->join('yjy_project p', 'oi.pro_id=p.pro_id','LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id=d.id','LEFT')
                    ->where('dr.id', 'in',$ids)
                    ->order('dr.id', 'DESC')
                    ->select();
            if($data){
                $this->view->assign('data',$data);
            }
        }else if($this->request->isGet() && $printType==1){
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $ids = explode(',', $ids);
            $where=[];
            if($depot_id){
                $where['p.depot_id'] = $depot_id;
            }
            if($dept_id){
                $where['a.dept_id'] = $dept_id;
            }

            $allPdata = DB::table('yjy_deduct_records')->alias('dr')
                    ->field('dr.id as drid,p.pro_id,p.pro_name,p.pro_spec,sum(dr.deduct_times) as all_deduct_times,d.name as dname,u.name as uname')
                    ->join('yjy_order_items oi','dr.order_item_id=oi.item_id','LEFT')
                    ->join('yjy_project p', 'oi.pro_id=p.pro_id','LEFT')
                    ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id=d.id','LEFT')
                    ->where('oi.customer_id', 'in',$ids)
                    ->where('dr.status', 1)
                    ->where('oi.item_type', '1')
                    ->where($where)
                    ->group('p.pro_id')
                    ->order('dr.id', 'DESC')
                    ->select();
                    // var_dump($allPdata);
            if($allPdata){
                $this->view->assign('allPdata',$allPdata);
            }
        }
        return $this->view->fetch();
    }





    /**
     *  待发药处方列表
     */
    public function index_one()
    {
         if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $extraWhere = ['order_items.item_type' => \app\admin\model\Project::TYPE_MEDICINE];
            $total = \app\admin\model\OrderItems::getUndeliverdListCount($where, $extraWhere);
            $list = \app\admin\model\OrderItems::getUndeliverdList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     *  已发药处方列表
     */
    public function index_two()
    {
         if ($this->request->isAjax())
        {
            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            $extraWhere =[];
            if (!empty($filter['stime']) && !empty($filter['etime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                // $mapt['createtime'] = array("between",array($startr,$endr));
                $extraWhere['s.sltime'] = array("between",array($startr,$endr));
                
            }$extraWhere['order_items.item_type'] = \app\admin\model\Project::TYPE_MEDICINE;

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // var_dump($extraWhere);die();
            $total = \app\admin\model\OrderItems::getdeliverdListCount($where, $extraWhere);
            $list = \app\admin\model\OrderItems::getdeliverdList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    
   

    
    
	

    
    /**
     * 编辑
     */
    public function edit_one($ids = NULL)
    {
        if ($this->request->isGet())
        {
            $ids = input('ids');
            // 划扣金额 deduct_amount ==  stocklog-->slallprice
            // 划扣次数=数量 deduct_times == slnum;  sltype == 6(发药)
            if($ids){
                $data = db('deduct_records')->alias('dr')
                        ->field('dr.id, dr.order_item_id, dr.deduct_times, dr.deduct_amount, oi.pro_id, oi.pro_name,oi.customer_id, lot.lstock, lot.lot_id,lot.lotnum, lot.lcost,lot.letime, pro.pro_unit, pro.pro_spec,pro.pro_code, u.name as uname')
                        ->join('yjy_order_items oi', 'dr.order_item_id = oi.item_id', 'LEFT')
                        ->join('yjy_project pro', 'oi.pro_id = pro.pro_id', 'LEFT')
                        ->join('yjy_wm_lotnum lot', 'pro.pro_id = lot.lpro_id', 'LEFT')
                        ->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
                        ->where('dr.id', $ids)
                        ->where('lot.lstock', '>','0')
                        ->order('lot.letime', 'ASC')
                        ->select();
            }else{
                $data =[];
            }
            // var_dump($data);

        }
        if($this->request->isAjax()){
            $lot_id = $this->request->post('lot_id');
            if($lot_id){
                $msg = db('wm_lotnum')->alias('l')
                        ->field('l.lot_id,l.lotnum,l.lcost,l.lprice,l.lstock,l.lstime,l.letime,p.pro_name,p.pro_spec,u.name as uname')
                        ->join('yjy_project p', 'l.lpro_id = p.pro_id', 'LEFT')
                        ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                        ->where('l.lot_id', $lot_id)
                        ->find();
                $msg['letime'] = $msg['letime']>0?date('Y-m-d',$msg['letime']):'';
                $msg['lstime'] = $msg['lstime']>0?date('Y-m-d',$msg['lstime']):'';
                
            }else{
                $msg =[];
            }
            return json($msg);
        }

        
        $this->view->assign('data', $data);
        return $this->view->fetch();
    }


    public function edit_two($ids = NULL)
    {
        if ($this->request->isGet())
        {
            $ids = input('ids');
            // 划扣金额 deduct_amount ==  stocklog-->slallprice
            // 划扣次数=数量 deduct_times == slnum;  sltype == 6(发药)


            if($ids){
                $data = db('wm_recipe')->alias('r')
                        ->field('r.re_id,sl.sldr_id, sl.slcustomer_id, sl.slnum, sl.slotid, p.pro_name, p.pro_spec, lot.lotnum,lot.lstime, lot.letime,lot.lstock, sl.slcost,sl.slprice, u.name as uname')
                        ->join('yjy_wm_stocklog sl', 'r.rsl_id = sl.sl_id', 'LEFT')
                        ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                        ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                        ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                        ->where('r.rdr_id', $ids)
                        ->order('r.re_id', 'ASC')
                        ->select();
            }else{
                $data =[];
            }

        }
        
        $this->view->assign('data', $data);
        return $this->view->fetch();
    }


    
    /**
     * 确认发药
     */
    public function dispensing(){
        if($this->request->isPost()){
            $customer_id = $this->request->post('customer_id');     //顾客
            $dr_id = $this->request->post('dr_id');     //划扣id
            
            $deduct_times = $this->request->post('deduct_times');     //数量
            $lot_id = $this->request->post('lot_id/a');
            $mpro_num = $this->request->post('mpro_num/a');
            $mcost = $this->request->post('mcost/a');
            $mprice = $this->request->post('mprice/a');

            $stocklogData = array();        //产品变动明细表数据
            $overStock = [];            //当前库存
            // $sldata = [];

            $stocklogData['slexplain'] = '发药划扣单'.$dr_id;
            $stocklogData['sltype'] = '6';      //发药
            $stocklogData['dr_status'] = '2'; # 1: 未发药  2：已发药
            $stocklogData['dr_id'] = $dr_id;
            $stocklogData['customer_id'] = $customer_id;

            if(!$lot_id){       //产品必选
                $this->error('请选择药品批号！');
            }
            foreach ($lot_id as $k => $v) {
                $overStock[$k]['lot_id'] = $v;
                $allstock[$k] = db('wm_lotnum')->alias('l')         //查询批号的当前库存和关联的产品的当前总库存
                    ->field('l.lstock,p.pro_stock,p.pro_id')
                    ->join('yjy_project p', 'l.lpro_id = p.pro_id')
                    ->where('l.lot_id', $v)
                    ->select();
            }
            foreach ($allstock as $key => $value) {
                foreach ($value as $ke => $va) {
                    $overStock[$key]['lstock'] = $va['lstock'];
                    $overStock[$key]['pro_stock'] = $va['pro_stock'];
                    $overStock[$key]['pro_id'] = $va['pro_id'];
                }
                
            }
            
            foreach ($mpro_num as $k => $v) {
                if($v ==''){
                    $this->error('发药数量必填！');
                }
                
                $intNum = intval($v);
                $floatNum = floatval($v);
                if($floatNum <1 || $intNum != $floatNum){
                    $this->error('请输入正确的发药数量！');
                }
                if(intval($v)>$overStock[$k]['lstock']){
                    $this->error('发药数量不能大于当前库存数！');
                }
                $overStock[$k]['mpro_num'] = $v;
                // $stocklogData[$k]['slrest'] = '';
            }

            $totalNum = array_sum($mpro_num);
            if($totalNum != $deduct_times){       
                $this->error('发药数量不正确！');
            }

            foreach($mcost as $k => $v){
                $floatNum = floatval($v);
                if($v ==''){
                    $this->error('进价必填！');
                }
                if($floatNum <0){
                    $this->error('请输入正确的进价！');
                }
                $overStock[$k]['mcost'] = $v;
                $overStock[$k]['mallcost'] = $v * $mpro_num[$k];
            }

            foreach($mprice as $k => $v){
                $overStock[$k]['mprice'] = $v;
                $overStock[$k]['mallprice'] = $v * $mpro_num[$k];
            }

            if($overStock){
                \think\Db::startTrans();                //开启db回滚;
                $res = ['error' => false, 'msg' => ''];     //设置回滚状态及信息
                foreach ($overStock as $key => $v) {
                    $overStock[$key]['slrest'] = $v['lstock']-$v['mpro_num'];
                    $changLotnumStock = db('wm_lotnum')->where('lot_id',$v['lot_id'])->update(['lstock' => $v['lstock']-$v['mpro_num']]);

                    if($changLotnumStock ==''){
                        $res = ['error' => true, 'msg' => '保存失败！changLotnumStock'];
                        break;
                    }
                }
                $changProStock = db('project')->where('pro_id',$overStock[0]['pro_id'])->update(['pro_stock' => $overStock[0]['pro_stock']-$totalNum]);
                if($changProStock ==''){
                    $res = ['error' => true, 'msg' => '保存失败！changProStock'];
                                        break;
                }
// var_dump($overStock);
                $stockLogRes = model('StockLog')->add_stocklog($overStock,$stocklogData);
                if($stockLogRes == '2'){
                    $res = ['error' => true, 'msg' => '保存失败！stockLogRes'];
                    break;
                }

                if($res['error'] == false){
                    \think\Db::commit();
                    $this->success();
                }else{
                    \think\Db::rollback();
                    $this->error($res['msg']);
                }

            }

            
        }
    }



    /**
     * 撤销发药
     */
    public function revoke(){

        if($this->request->isPost()){
            $customer_id = $this->request->post('customer_id');     //顾客
            $dr_id = $this->request->post('dr_id');     //划扣id

            $lot_id = $this->request->post('lot_id/a');
            $re_id = $this->request->post('re_id/a');
            $mpro_num = $this->request->post('mpro_num/a');
            $mcost = $this->request->post('mcost/a');
            $mprice = $this->request->post('mprice/a');

            $stocklogData = array();        //产品变动明细表数据
            $overStock = [];            //当前库存
            // $sldata = [];

            $stocklogData['slexplain'] = '撤药划扣单'.$dr_id;
            $stocklogData['sltype'] = '7';      //撤药
            $stocklogData['dr_status'] = '1'; # 1: 未发药  2：已发药
            $stocklogData['dr_id'] = $dr_id;
            $stocklogData['customer_id'] = $customer_id;

            $totalNum = array_sum($mpro_num);
            foreach ($mpro_num as $k => $v) {
                $overStock[$k]['mpro_num'] = $v;
            }
            foreach ($re_id as $k => $v) {
                $overStock[$k]['re_id'] = $v;
            }
            foreach($mcost as $k => $v){
                
                $overStock[$k]['mcost'] = $v;
                $overStock[$k]['mallcost'] = $v * $mpro_num[$k];
            }

            foreach($mprice as $k => $v){
                
                $overStock[$k]['mprice'] = $v;
                $overStock[$k]['mallprice'] = $v * $mpro_num[$k];
            }

            foreach ($lot_id as $k => $v) {
                $overStock[$k]['lot_id'] = $v;
                $allstock[$k] = db('wm_lotnum')->alias('l')         //查询批号的当前库存和关联的产品的当前总库存
                    ->field('l.lstock,p.pro_stock,p.pro_id')
                    ->join('yjy_project p', 'l.lpro_id = p.pro_id')
                    ->where('l.lot_id', $v)
                    ->select();
            }
            foreach ($allstock as $key => $value) {
                foreach ($value as $ke => $va) {
                    $overStock[$key]['lstock'] = $va['lstock'];
                    $overStock[$key]['pro_stock'] = $va['pro_stock'];
                    $overStock[$key]['pro_id'] = $va['pro_id'];
                }
                
            }



            if($overStock){
                \think\Db::startTrans();                //开启db回滚;
                $res = ['error' => false, 'msg' => ''];     //设置回滚状态及信息
                foreach ($overStock as $key => $v) {
                    $overStock[$key]['slrest'] = $v['lstock']+$v['mpro_num'];
                    $changLotnumStock = db('wm_lotnum')->where('lot_id',$v['lot_id'])->update(['lstock' => $v['lstock']+$v['mpro_num']]);

                    if($changLotnumStock ==''){
                        $res = ['error' => true, 'msg' => '保存失败！changLotnumStock'];
                        break;
                    }
                }
                $changProStock = db('project')->where('pro_id',$overStock[0]['pro_id'])->update(['pro_stock' => $overStock[0]['pro_stock']+$totalNum]);
                if($changProStock ==''){
                    $res = ['error' => true, 'msg' => '保存失败！changProStock'];
                                        break;
                }
// var_dump($overStock);
                $stockLogRes = model('StockLog')->add_stocklog($overStock,$stocklogData);
                if($stockLogRes == '2'){
                    $res = ['error' => true, 'msg' => '保存失败！stockLogRes'];
                    break;
                }

                if($res['error'] == false){
                    \think\Db::commit();
                    $this->success();
                }else{
                    \think\Db::rollback();
                    $this->error($res['msg']);
                }

            }


        }
    
    }
}
