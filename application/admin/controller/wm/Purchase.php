<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 进货单
 *
 * @icon fa fa-circle-o
 */
class Purchase extends Backend
{
    
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Purchase');

        $supplierList = db('wm_supplier')->where('sup_type' ,'<>','1')->where('sup_status','1')->select();
        $this->view->assign('supplierList', $supplierList);

        $depotList = db('depot')->where(['type'=>'2', 'status'=>'normal'])->select();
        $this->view->assign('depotList', $depotList);
    }

    public function index(){
        if ($this->request->isAjax())
        {   
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            $search = $this->request->request("search");

            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                $map['pur.mcreatetime'] = array("between",array($startr,$endr));
                $mapTotal['mcreatetime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $mapTotal= [];
            }
            
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($mapTotal)->where($where)
                    ->count();

            $lists = $this->model->alias('pur')
                    ->field('pur.*, sup.sup_name, dpt.name as dpt_name')
                    ->join('yjy_wm_supplier sup', 'pur.msupplier_id = sup.sup_id', 'LEFT')
                    ->join('yjy_depot dpt', 'pur.mdepot_id = dpt.id', 'LEFT')
                    ->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            

            $result = array("total" => $total, "rows" => $lists);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function add(){
        if($this->model->select()){
            $num=$this->model->field('max(man_id) as id')->find();
            $nums = $num['id']+1;
            $man_num = 'CG'.$nums;
            $this->view->assign("man_num", $man_num);
        }else{
            $this->view->assign("man_num", 'CG1');
        }

        if($this->request->isPost()){
            $params = $this->request->post("row/a");            //货单表数据
            $params['mcreatetime'] = time();
            if($params){
                // var_dump($params);die();
                $man_nums = $params['man_num'];
                $searchMan_num = $this->model->where('man_num',$man_nums)->find();
                if($searchMan_num){
                    $this->error('本次采购单号已存在，请重新获取！');
                }else{
                    $pro_id = $this->request->post("lpro_id/a");
                    $lotnum = $this->request->post('lotnum/a');
                    $mpro_num = $this->request->post('mpro_num/a');
                    $mcost = $this->request->post('mcost/a');
                    $mallcost = $this->request->post('mallcost/a');
                    $mprice = $this->request->post('mprice/a');
                    $mallprice = $this->request->post('mallprice/a');

                    $lstime = $this->request->post("lstime/a");
                    $letime = $this->request->post("letime/a");
                    $lproducer = $this->request->post("lproducer/a");
                    $laddr = $this->request->post("laddr/a");
                    $lapprov_num = $this->request->post("lapprov_num/a");
                    $lregist_num = $this->request->post("lregist_num/a");


                    $manlistData = array();     //货单明细表数据
                    $lotnumData = array();      //产品批号表数据

                    if(!$pro_id){       //产品必选
                        $this->error('请选择产品！');
                    }
                    foreach ($pro_id as $k => $v) {
                        $lotnumData[$k]['lpro_id'] = $v;
                    }
                    
                    foreach ($lotnum as $k => $v) {
                        // if($v ==''){
                        //  $this->error('批号必填！');
                        // }
                        $lotnumData[$k]['lotnum'] = $v;
                    }
                    
                    
                    foreach ($mpro_num as $k => $v) {
                        if($v ==''){
                            $this->error('数量必填！');
                        }
                        $intNum = intval($v);
                        $floatNum = floatval($v);
                        if($floatNum <1 || $intNum != $floatNum){
                            $this->error('请输入正确的进货数量！');
                        }
                        $manlistData[$k]['mpro_num'] = $v;
                        $lotnumData[$k]['lstock'] = $v;
                    }
                    
                    foreach($mcost as $k => $v){
                        $floatmcost = floatval($v);
                        if($v ==''){
                            $this->error('进价必填！');
                        }
                        if (preg_match('/^[0-9]+(\.[0-9]{1,4})?$/', $floatmcost) ==0) {
                            $this->error('请输入正确的进价！');
                        }
                        $manlistData[$k]['mcost'] = $v;
                        $lotnumData[$k]['lcost'] = $v;
                    }
                    foreach($mallcost as $k => $v){
                        $manlistData[$k]['mallcost'] = $v;
                    }

                    
                    foreach($mprice as $k => $v){
                        $floatmprice = floatval($v);
                        if($v ==''){
                            $this->error('售价必填！');
                        }
                        if (preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $floatmprice) ==0) {
                            $this->error('请输入正确的售价！');
                        }
                        $manlistData[$k]['mprice'] = $v;
                        $lotnumData[$k]['lprice'] = $v;
                    }
                    foreach($mallprice as $k => $v){
                        $manlistData[$k]['mallprice'] = $v;
                    }

                    foreach ($lstime as $k => $v){
                        $manlistData[$k]['mstime'] = strtotime($v);
                        $lotnumData[$k]['lstime'] = strtotime($v);
                    }
                    foreach ($letime as $k => $v){
                        $manlistData[$k]['metime'] = strtotime($v);
                        $lotnumData[$k]['letime'] = strtotime($v);
                    }
                    foreach ($lproducer as $k => $v){
                        $lotnumData[$k]['lproducer'] = $v;
                    }
                    foreach ($laddr as $k => $v){
                        $lotnumData[$k]['laddr'] = $v;
                    }
                    foreach ($lapprov_num as $k => $v){
                        $lotnumData[$k]['lapprov_num'] = $v;
                    }
                    foreach ($lregist_num as $k => $v){
                        $lotnumData[$k]['lregist_num'] = $v;
                        $lotnumData[$k]['lsupplier_id'] = $params['msupplier_id'];
                    }


                    if($manlistData && $lotnumData){

                        \think\Db::startTrans();                //开启db回滚;
                        $res = ['error' => false, 'msg' => ''];     //设置回滚状态及信息

                        $manifestRes = $this->model->save($params);
                        if($manifestRes == false){

                            $res = ['error' => true, 'msg' => '保存失败！manifestRes'];
                            break;

                        }elseif($manifestRes !== false){
                            $man_id = $this->model->man_id;         //取得货单表id
                            if(!$man_id){
                                $res = ['error' => true, 'msg' => '保存失败！man_id'];
                                break;
                            }else{
                                foreach ($manlistData as $k => $v) {
                                    $manlistData[$k]['manid'] = $man_id;        //货单id
                                }

                                $lotnumRes = [];
                                foreach ($lotnumData as $k => $v) {
                                    db('wm_purlot')->insert($v);            //返回保存的产品批号的id
                                    $lotnumRes[$k]['lotid'] = db('wm_purlot')->getLastInsID();
                                }
                                
                                if(!$lotnumRes){
                                    $res = ['error' => true, 'msg' => '保存失败！lotnumRes'];
                                    break;
                                }else{
                                    
                                    foreach ($lotnumRes as $k => $v) {
                                        if($v ==''){
                                            $res = ['error' => true, 'msg' => '保存失败！lotnumRes->v'];
                                            break;
                                        }else{
                                            $manlistData[$k]['lotid'] = $v['lotid'];            //产品批号id
                                        }
                                    }
                                    // dump($lotnumData);die();
                                    $manlistRes = db('wm_purlist')->insertAll($manlistData);    //保存数据到货单明细表
                                    if(!$manlistRes){
                                        $res = ['error' => true, 'msg' => '保存失败！manlistRes'];
                                        break;
                                    }
                                }

                            }
                            
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


        return $this->view->fetch();
    }


    public function edits($ids = NULL){
        $row = $this->model->find($ids);
        if (!$row){
            $this->error(__('No Results were found'));
        }
        $list = db('wm_purlist')->alias('pl')
                ->field('pl.*,lotnum.lotnum,lotnum.lproducer,lotnum.laddr,lotnum.lapprov_num,lotnum.lregist_num,pro.pro_name,pro.pro_unit,pro.pro_spec,u.name as uname')
                ->join('yjy_wm_purlot lotnum', 'pl.lotid = lotnum.lot_id', 'LEFT')
                ->join('yjy_project pro', 'lotnum.lpro_id = pro.pro_id', 'LEFT')
                ->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
                ->where('manid', $ids)
                ->select();
                // var_dump($list);die();
        $totalCost = '';
        foreach ($list as $key => $value) {
            $totalCost += $value['mallcost'];
        }
        
        $this->view->assign('row',$row);
        $this->view->assign('totalCost',$totalCost);

        if($list){
            $this->view->assign('list',$list);
        }
        
        return $this->view->fetch();
    }

    public function proSearch(){
        $list = [];
        if($this->request->isAjax()){
            $keywords = $this->request->post('keywords');
            $depot = $this->request->post('depot');
            $where['p.depot_id'] = $depot;

            if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keywords)>0){// 搜索中文关键词
                $where['p.pro_name'] = ['like', '%'.$keywords.'%'];

            }/*elseif(preg_match('/^[1-9][0-9]*$/',$keywords)){// 搜索产品id关键词
                $where['p.pro_id'] = $keywords;
            }*/
            else{                                               // 搜索产品拼音码关键词
                $where['p.pro_spell'] = ['like', '%'.$keywords.'%'];
            }

            $where['p.pro_status'] = '1';
            $where['p.pro_type'] = '2';
            if($keywords && $depot){
                $list = db('project')->alias('p')
                        ->join('yjy_unit u','p.pro_unit = u.id', 'LEFT')
                        ->where($where)
                        ->order('pro_spell','asc')
                        ->column('p.pro_id,p.pro_name,p.pro_spell,u.name,p.pro_spec,p.pro_amount,p.pro_cost,p.addr,p.producer,p.regist_num,p.approv_num');
                // var_dump($list);

            }
        }

        return json($list);
    }



    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(man_id) as id')->find();
                $numss = $nums['id']+1;
                $wpjh_num = 'CG'.$numss;
                // var_dump($yp_num);
                return json($wpjh_num);
            }

        }
    }

    public function editRemark(){
        if($this->request->isAjax()){
            $manid = $this->request->post('manid');
            $mremark = $this->request->post('mremark');
            $msupplier_id = $this->request->post('msupplier_id');
            $manlist = db('wm_purlist')->alias('ml')
                        ->field('lotid,man_num')
                        ->join('yjy_wm_purchase mf','ml.manid =mf.man_id','LEFT')
                        ->where('ml.manid',$manid)
                        ->select();
            // return json($manlist);
            if($manlist){
                $editManRemarkRes=db('wm_purchase')->where('man_id',$manid)->update(['mremark'=>$mremark,'msupplier_id'=>$msupplier_id]);

                if($editManRemarkRes){
                    return json(1);
                }else{
                    return json(2);
                }
            }else{
                return json(3);
            }

        }
    }

    public function delPurList(){
        if($this->request->isAjax()){
            $plid = $this->request->post('plid');

            $purlist = DB::table('yjy_wm_purlist')->field('lotid')
                        ->where('ml_id',$plid)
                        ->find();
            // return json($purlist);
            if($purlist){
                $delPurList=DB::table('yjy_wm_purlist')->where('ml_id',$plid)->delete();
                $delPurLot=DB::table('yjy_wm_purlot')->where('lot_id',$purlist['lotid'])->delete();

                if($delPurList && $delPurLot){
                    return json(1);
                }else{
                    return json(2);
                }
            }else{
                return json(3);
            }

        }
    }

    public function scrap(){
        if($this->request->isAjax()){
            $scrapId = $this->request->post('scrapId');
            $purData = $this->model->field('man_id')->where('man_id',$scrapId)->find();
            // return json($purData);
            if($purData){
                $delPur=$this->model->where('man_id',$scrapId)->update(['mstatus'=>3]);

                if($delPur){
                    return json(1);
                }else{
                    return json(2);
                }
            }else{
                return json(3);
            }

        }
    }


    public function verified(){
        if($this->request->isAjax()){
            $verified_id = $this->request->post('verified');
            $saveMfData = DB::table('yjy_wm_purchase')->field('muid,msupplier_id,mdepot_id,mremark')->where('man_id',$verified_id)->find();
            $man_num = Db::table('yjy_wm_manifest')->field('max(man_id) as id')->find();

            $man_num['id'] = $man_num['id']+1;
            $saveMfData['man_num'] = 'wpjh'.$man_num['id'];
            $saveMfData['mprimary_type'] = 1;
            $saveMfData['msecond_type'] = 1;
            $saveMfData['mbelong_type'] = 2;
            $saveMfData['mstatus'] = 1;
            $saveMfData['mcreatetime'] = time();
// return $saveMfData;

            $purListLotData = DB::table('yjy_wm_purlist')->alias('pl')
                            ->field('pl.mpro_num,pl.mcost,pl.mallcost,pl.mprice,pl.mallprice,pl.mstime,pl.metime,plot.lpro_id,plot.lsupplier_id,plot.lotnum,plot.lstock,plot.lcost,plot.lprice,plot.lproducer,plot.laddr,plot.lapprov_num,plot.lregist_num,plot.lstime,plot.letime')
                            ->join('yjy_wm_purlot plot', 'pl.lotid=plot.lot_id','LEFT')
                            ->where('manid',$verified_id)
                            ->select();
            // return json($purListLotData);

            if($saveMfData && $purListLotData){
                \think\Db::startTrans();                //开启db回滚;
                $res = ['error' => false];     //设置回滚状态及信息


                // $delPur=$this->model->where('man_id',$scrapId)->update(['mstatus'=>3]);
                $mfId = Db::name('wm_manifest')->insertGetId($saveMfData);
                // \think\Db::rollback();
                // return json($mfId);
                if(!$mfId){
                    $res = ['error' => true];
                    dbStartTrans($res);
                }

                $lotnumData = [];
                $manlistData = [];
                $stocklogData = [];

                $stocklogData['sletc'] = $saveMfData['man_num'];
                $stocklogData['slexplain'] = $verified_jhnum = '物品进货单'.$saveMfData['man_num'];
                $stocklogData['slremark'] = $saveMfData['mremark'];
                $stocklogData['sltype'] = '1';      //变动状态
                $stocklogData['smalltype'] = '1';   //次类型

                foreach ($purListLotData as $k => $v) {
                    $lotnumData[$k]['lpro_id'] = $v['lpro_id'];
                    $lotnumData[$k]['lsupplier_id'] = $v['lsupplier_id'];
                    $lotnumData[$k]['lotnum'] = $v['lotnum'];
                    $lotnumData[$k]['lstock'] = $v['lstock'];
                    $lotnumData[$k]['lcost'] = $v['lcost'];
                    $lotnumData[$k]['lprice'] = $v['lprice'];
                    $lotnumData[$k]['lproducer'] = $v['lproducer'];
                    $lotnumData[$k]['laddr'] = $v['laddr'];
                    $lotnumData[$k]['lapprov_num'] = $v['lapprov_num'];
                    $lotnumData[$k]['lregist_num'] = $v['lregist_num'];
                    $lotnumData[$k]['lstime'] = $v['lstime'];
                    $lotnumData[$k]['letime'] = $v['letime'];

                    $manlistData[$k]['mpro_num'] = $v['mpro_num'];
                    $manlistData[$k]['mcost'] = $v['mcost'];
                    $manlistData[$k]['mallcost'] = $v['mallcost'];
                    $manlistData[$k]['mprice'] = $v['mprice'];
                    $manlistData[$k]['mallprice'] = $v['mallprice'];
                    $manlistData[$k]['mstime'] = $v['mstime'];
                    $manlistData[$k]['metime'] = $v['metime'];
                    $manlistData[$k]['manid'] = $mfId;

                }

                foreach ($lotnumData as $k => $v) {
                    $manlistData[$k]['lotid'] = Db::name('wm_lotnum')->insertGetId($v);
                    $pro_stock = DB::table('yjy_project')->field('pro_stock')->where('pro_id',$v['lpro_id'])->find();
                    $proRes = DB::table('yjy_project')->where('pro_id',$v['lpro_id'])->setInc('pro_stock',$v['lstock']);
                    $stocklogData[$k]['slrest'] = $pro_stock['pro_stock']+$v['lstock'];
                }

                if(!$proRes){
                    $res = ['error' => true];
                    return $this->model->dbStartTrans($res);
                }

                $manlistRes = DB::table('yjy_wm_manlist')->insertAll($manlistData);
                $stockLogRes = model('StockLog')->add_stocklog($manlistData,$stocklogData);
                $purRes = DB::table('yjy_wm_purchase')->where('man_id',$verified_id)->update(['mstatus'=>2,'verified_jhnum'=>$verified_jhnum]);
                if($manlistRes && $stockLogRes && $purRes){
                    return json($this->model->dbStartTrans($res));
                }else{
                    $res = ['error' => true];
                    return json($this->model->dbStartTrans($res));
                }



                
            }else{
                return json(3);
            }

        }
    }


    

}