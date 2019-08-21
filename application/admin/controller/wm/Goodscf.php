<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Goodscf extends Backend
{
    
    /**
     * DepotOutks模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     *  待出料处方列表
     */
    public function index_one()
    {
         if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $extraWhere = ['order_items.item_type' => \app\admin\model\Project::TYPE_PRODUCT];
            $total = \app\admin\model\OrderItems::getUndeliverdListCount($where, $extraWhere);
            $list = \app\admin\model\OrderItems::getUndeliverdList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     *  已出料处方列表
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
                
            }$extraWhere['order_items.item_type'] = \app\admin\model\Project::TYPE_PRODUCT;

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
    public function edit_one($ids = NULL,$type=1)
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
        $this->view->assign('type', $type);
        return $this->view->fetch();
    }


    public function edit_two($ids = NULL,$type=1)
    {
        if ($this->request->isGet())
        {
            $ids = input('ids');
            // 划扣金额 deduct_amount ==  stocklog-->slallprice
            // 划扣次数=数量 deduct_times == slnum;  sltype == 6(发出)


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
        $this->view->assign('type', $type);
        return $this->view->fetch();
    }


    
    /**
     * 确认发料
     */
    public function dispensing(){
        if($this->request->isPost()){
            $type = $this->request->post('type');
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

            $stocklogData['slexplain'] = '发料划扣单'.$dr_id;
            $stocklogData['sltype'] = '6';      //发出
            $stocklogData['dr_status'] = '2'; # 1: 未发  2：已发
            $stocklogData['dr_id'] = $dr_id;
            $stocklogData['customer_id'] = $customer_id;

            if(!$lot_id){       //产品必选
                $this->error('请选择物品批号！');
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
                    $this->error('发料数量必填！');
                }
                
                $intNum = intval($v);
                $floatNum = floatval($v);
                if($floatNum <1 || $intNum != $floatNum){
                    $this->error('请输入正确的发料数量！');
                }
                if(intval($v)>$overStock[$k]['lstock']){
                    $this->error('发料数量不能大于当前库存数！');
                }
                $overStock[$k]['mpro_num'] = $v;
                // $stocklogData[$k]['slrest'] = '';
            }

            $totalNum = array_sum($mpro_num);
            if($totalNum != $deduct_times){       
                $this->error('发料数量不正确！');
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
                if($type==1){
                    $res = ['error' => false, 'msg' => '发药成功！'];
                }else{
                    $res = ['error' => false, 'msg' => '1'];
                }
                foreach ($overStock as $key => $v) {
                    $overStock[$key]['slrest'] = $v['lstock']-$v['mpro_num'];
                    $changLotnumStock = db('wm_lotnum')->where('lot_id',$v['lot_id'])->update(['lstock' => $v['lstock']-$v['mpro_num']]);

                    if($changLotnumStock ==''){
                        if($type==1){
                            $res = ['error' => true, 'msg' => '保存失败！changLotnumStock'];
                        }else{
                            $res = ['error' => true, 'msg' => '2'];
                        }
                        break;
                    }
                }
                $changProStock = db('project')->where('pro_id',$overStock[0]['pro_id'])->update(['pro_stock' => $overStock[0]['pro_stock']-$totalNum]);
                if($changProStock ==''){
                    if($type==1){
                        $res = ['error' => true, 'msg' => '保存失败！changProStock'];
                    }else{
                        $res = ['error' => true, 'msg' => '2'];
                    }
                    break;
                }
// var_dump($overStock);
                $stockLogRes = model('StockLog')->add_stocklog($overStock,$stocklogData);
                if($stockLogRes == '2'){
                    if($type==1){
                        $res = ['error' => true, 'msg' => '保存失败！stockLogRes'];
                    }else{
                        $res = ['error' => true, 'msg' => '2'];
                    }
                    break;
                }

                if($type==1){
                    if($res['error'] == false){
                        \think\Db::commit();
                        $this->success($res['msg']);
                    }else{
                        \think\Db::rollback();
                        $this->error($res['msg']);
                    }
                }else{
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
    }



    /**
     * 撤销
     */
    public function revoke(){

        if($this->request->isPost()){
            $customer_id = $this->request->post('customer_id');     //顾客
            $dr_id = $this->request->post('dr_id');     //划扣id
            $type = $this->request->post('type');

            $lot_id = $this->request->post('lot_id/a');
            $re_id = $this->request->post('re_id/a');
            $mpro_num = $this->request->post('mpro_num/a');
            $mcost = $this->request->post('mcost/a');
            $mprice = $this->request->post('mprice/a');

            $stocklogData = array();        //产品变动明细表数据
            $overStock = [];            //当前库存
            // $sldata = [];

            $stocklogData['slexplain'] = '撤料划扣单'.$dr_id;
            $stocklogData['sltype'] = '7';      //撤回
            $stocklogData['dr_status'] = '1'; # 1: 未发药  2：已发
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
                if($type==1){
                    $res = ['error' => false, 'msg' => '撤料成功！'];
                }else{
                    $res = ['error' => false, 'msg' => '1'];
                }
                
                foreach ($overStock as $key => $v) {
                    $overStock[$key]['slrest'] = $v['lstock']+$v['mpro_num'];
                    $changLotnumStock = db('wm_lotnum')->where('lot_id',$v['lot_id'])->update(['lstock' => $v['lstock']+$v['mpro_num']]);

                    if($changLotnumStock ==''){
                        if($type==1){
                            $res = ['error' => true, 'msg' => '保存失败！changLotnumStock'];
                        }else{
                            $res = ['error' => true, 'msg' => '2'];
                        }
                        break;
                    }
                }
                $changProStock = db('project')->where('pro_id',$overStock[0]['pro_id'])->update(['pro_stock' => $overStock[0]['pro_stock']+$totalNum]);
                if($changProStock ==''){
                    if($type==1){
                        $res = ['error' => true, 'msg' => '保存失败！changProStock'];
                    }else{
                        $res = ['error' => true, 'msg' => '2'];
                    }
                    break;
                }
// var_dump($overStock);
                $stockLogRes = model('StockLog')->add_stocklog($overStock,$stocklogData);
                if($stockLogRes == '2'){
                    if($type==1){
                        $res = ['error' => true, 'msg' => '保存失败！stockLogRes'];
                    }else{
                        $res = ['error' => true, 'msg' => '2'];
                    }
                    break;
                }

                if($type==1){
                    if($res['error'] == false){
                        \think\Db::commit();
                        $this->success($res['msg']);
                    }else{
                        \think\Db::rollback();
                        $this->error($res['msg']);
                    }
                }else{
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
    
    }
}
