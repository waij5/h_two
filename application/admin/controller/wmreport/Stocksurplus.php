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
class Stocksurplus extends Backend
{
    /**查询结束日期大于等于今天则 只需$wheres条件stocklog数据，否则要查出结束日期距今天的日期的otherslData, surplusData-sl-otherslData 来反推期末、期初
     * Unit模型对象
     */
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
            $wheres = [];
            $otherWhere = [];
            //更新20190111
            $postData['stime'] = !empty($postData['stime'])?$postData['stime']:'2017-01-01';
            $postData['etime'] = !empty($postData['etime'])?$postData['etime']:date('Y-m-d');
            //更新20190111
            if($postData['stime'] && $postData['etime']){
                $censusDate = '查询日期：'.$postData['stime'].' 至 '.$postData['etime'];
                $this->view->assign('censusDate', $censusDate);
                $wheres['sl.sltime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
            }
            
            if($postData['depot_id']){
                $where['p.depot_id'] = $postData['depot_id'];
                $wheres['p.depot_id'] = $postData['depot_id'];
                $otherWhere['p.depot_id'] = $postData['depot_id'];
            }
            if($postData['p_num'] != null){
                $where['p.pro_code'] = $postData['p_num'];
                $wheres['p.pro_code'] = $postData['p_num'];
                $otherWhere['p.pro_code'] = $postData['p_num'];
            }
            if($postData['p_name']){
                $where['p.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
                $wheres['p.pro_name'] = array('like','%'.$postData['p_name'].'%');
                $otherWhere['p.pro_name'] = array('like','%'.$postData['p_name'].'%');
            }
            // var_dump($wheres['sl.sltime'][1][1]);
            // $w = date('Y-m-d',$wheres['sl.sltime'][1][1]);
            // $w = $wheres;
            // unset($w['sl.sltime']);
            // $ws = $w;
            // var_dump($ws);
            

            $nowDate = date('Y-m-d');
            $otherSlData='';
            if($nowDate > $postData['etime']){
                $otherWhere['sl.sltime'] = ['between',[strtotime($postData['etime'].'23:59:59')+1,strtotime($nowDate.'23:59:59')]];
                // $w['sl.sltime'] = ['between',[strtotime($postData['etime'].'23:59:59'),strtotime($nowDate.'23:59:59')]];

                $otherSlData = db('wm_stocklog')->alias('sl')
                                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec, p.pro_cat1, sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                                ->where($otherWhere)
                                ->order('p.pro_id','ASC')
                                ->select();
                                
                $otherSlData = model('Manifest')->changepoolsArrDeal($otherSlData,'1');

            }


            

            $proData = db('project')->alias('p')
                        ->field('p.pro_id, p.pro_name, p.pro_code, p.pro_unit, p.pro_stock, p.pro_spec, p.pro_cat1, u.name as uname')
                        ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                        ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                        ->where('p.pro_type', '<>','9')
                        ->where($where)
                        ->order('p.pro_id', 'DESC')
                        ->select();

            $slData = db('wm_stocklog')->alias('sl')
                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec, p.pro_cat1, sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                ->where($wheres)
                ->order('p.pro_id','ASC')
                ->select();

            $datasss = model('Manifest')->changepoolsArrDeal($slData,'1');

            $proDatas =[];
            $mergeData =[];
            $mergeDatas =[];
            $mergeOtherData =[];
            $mergeOtherDatas =[];
            // $issetOtherData ='';            
            if($proData && $datasss){
                foreach ($proData as $key => $value) {
                    $proDatas[$value['pro_id']] = $value;
                    // $slDatas[$value['lot_id']] = 0;
                }
                unset($proData);

                foreach ($proDatas as $k => $v) {
                    if(isset($datasss[$k])){
                        $mergeData[] = array_merge($proDatas[$k], $datasss[$k]);
                    }else {
                        $mergeData[] = $v;
                    }

                    if ($otherSlData) {
                        if(isset($otherSlData[$k])){
                            $mergeOtherData[] = array_merge($proDatas[$k], $otherSlData[$k]);
                        }else{
                            $mergeOtherData[] = $v;
                        }
                    }
                    
                }

                unset($proDatas);unset($otherSlData);


            }

// var_dump($nowDate);die();
            if($mergeData && $mergeOtherData){
                // $issetOtherData = '1';
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                foreach ($mergeOtherData as $k => $v) {
                    $mergeOtherDatas[$v['pro_id']] = $v;
                }

                unset($mergeOtherData);

                $mergeDatas = model('Manifest')->stockTypeDeal($mergeDatas);
                $mergeOtherDatas = model('Manifest')->stockTypeDeal($mergeOtherDatas);

                foreach($mergeDatas as $k => $v){           //将Other数据存入slData
                    $surplusProData[$k] = db('project')->alias('p')
                                        ->field('p.pro_id, p.pro_cat1, l.lot_id, l.lcost, l.lprice, l.lstock')
                                        ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                                        ->where('l.lstock','>','0')
                                        ->where('p.pro_id',$k)
                                        ->select();

                    $mergeDatas[$k]['otherjh'] = $mergeOtherDatas[$k]['jh'];
                    $mergeDatas[$k]['otherjhallcost'] = $mergeOtherDatas[$k]['jhallcost'];
                    $mergeDatas[$k]['otherjhallprice'] =$mergeOtherDatas[$k]['jhallprice'];

                    $mergeDatas[$k]['otherqtrk'] = $mergeOtherDatas[$k]['qtrk'];
                    $mergeDatas[$k]['otherqtrkallcost'] = $mergeOtherDatas[$k]['qtrkallcost'];
                    $mergeDatas[$k]['otherqtrkallprice'] =$mergeOtherDatas[$k]['qtrkallprice'];

                    $mergeDatas[$k]['otherdbrk'] = $mergeOtherDatas[$k]['dbrk'];
                    $mergeDatas[$k]['otherdbrkallcost'] = $mergeOtherDatas[$k]['dbrkallcost'];
                    $mergeDatas[$k]['otherdbrkallprice'] =$mergeOtherDatas[$k]['dbrkallprice'];

                    $mergeDatas[$k]['otherpyrk'] = $mergeOtherDatas[$k]['pyrk'];
                    $mergeDatas[$k]['otherpyrkallcost'] = $mergeOtherDatas[$k]['pyrkallcost'];
                    $mergeDatas[$k]['otherpyrkallprice'] =$mergeOtherDatas[$k]['pyrkallprice'];

                    $mergeDatas[$k]['otherthrk'] = $mergeOtherDatas[$k]['thrk'];
                    $mergeDatas[$k]['otherthrkallcost'] = $mergeOtherDatas[$k]['thrkallcost'];
                    $mergeDatas[$k]['otherthrkallprice'] =$mergeOtherDatas[$k]['thrkallprice'];

                    $mergeDatas[$k]['otherrkcj'] = $mergeOtherDatas[$k]['rkcj'];
                    $mergeDatas[$k]['otherrkcjallcost'] = $mergeOtherDatas[$k]['rkcjallcost'];
                    $mergeDatas[$k]['otherrkcjallprice'] =$mergeOtherDatas[$k]['rkcjallprice'];

                    $mergeDatas[$k]['otherpkcj'] = $mergeOtherDatas[$k]['pkcj'];
                    $mergeDatas[$k]['otherpkcjallcost'] = $mergeOtherDatas[$k]['pkcjallcost'];
                    $mergeDatas[$k]['otherpkcjallprice'] =$mergeOtherDatas[$k]['pkcjallprice'];

                    $mergeDatas[$k]['othergqcj'] = $mergeOtherDatas[$k]['gqcj'];
                    $mergeDatas[$k]['othergqcjallcost'] = $mergeOtherDatas[$k]['gqcjallcost'];
                    $mergeDatas[$k]['othergqcjallprice'] =$mergeOtherDatas[$k]['gqcjallprice'];

                    $mergeDatas[$k]['otherqtcj'] = $mergeOtherDatas[$k]['qtcj'];
                    $mergeDatas[$k]['otherqtcjallcost'] = $mergeOtherDatas[$k]['qtcjallcost'];
                    $mergeDatas[$k]['otherqtcjallprice'] =$mergeOtherDatas[$k]['qtcjallprice'];

                    $mergeDatas[$k]['otherly'] = $mergeOtherDatas[$k]['ly'];
                    $mergeDatas[$k]['otherlyallcost'] = $mergeOtherDatas[$k]['lyallcost'];
                    $mergeDatas[$k]['otherlyallprice'] =$mergeOtherDatas[$k]['lyallprice'];

                    $mergeDatas[$k]['otherll'] = $mergeOtherDatas[$k]['ll'];
                    $mergeDatas[$k]['otherllallcost'] = $mergeOtherDatas[$k]['llallcost'];
                    $mergeDatas[$k]['otherllallprice'] =$mergeOtherDatas[$k]['llallprice'];

                    $mergeDatas[$k]['otherfy'] = $mergeOtherDatas[$k]['fy'];
                    $mergeDatas[$k]['otherfyallcost'] = $mergeOtherDatas[$k]['fyallcost'];
                    $mergeDatas[$k]['otherfyallprice'] =$mergeOtherDatas[$k]['fyallprice'];

                    $mergeDatas[$k]['othercy'] = $mergeOtherDatas[$k]['cy'];
                    $mergeDatas[$k]['othercyallcost'] = $mergeOtherDatas[$k]['cyallcost'];
                    $mergeDatas[$k]['othercyallprice'] =$mergeOtherDatas[$k]['cyallprice'];
                    
                }

                unset($mergeOtherDatas);

                foreach ($surplusProData as $key => $value) {

                    $mergeDatas[$key]['allSurplusCost'] =0;
                    $mergeDatas[$key]['allSurplusPrice'] =0;
                    foreach ($value as $k => $va) {
                        $mergeDatas[$key]['allSurplusCost'] += $va['lcost']*$va['lstock'];
                        $mergeDatas[$key]['allSurplusPrice'] += $va['lprice']*$va['lstock'];
                    }
                    
                }


                // $fristPrice = 0;
                foreach($mergeDatas as $k => $v){

                    // $mergeDatas[$k]['fristCost'] = intval(100 * ($v['allSurplusCost'] - $v['jhallcost'] - $v['otherjhallcost'] - $v['qtrkallcost'] - $v['otherqtrkallcost'] - $v['dbrkallcost'] - $v['otherdbrkallcost'] - $v['pyrkallcost'] - $v['otherpyrkallcost'] - $v['thrkallcost'] - $v['otherthrkallcost'] + $v['rkcjallcost'] + $v['otherrkcjallcost'] + $v['pkcjallcost'] + $v['otherpkcjallcost'] + $v['gqcjallcost'] + $v['othergqcjallcost'] + $v['qtcjallcost'] + $v['otherqtcjallcost']+ $v['lyallcost'] + $v['otherlyallcost'] + $v['llallcost'] + $v['otherllallcost'])) / 100;
                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['otherjh']-$v['qtrk']-$v['otherqtrk']-$v['dbrk']-$v['otherdbrk']-$v['pyrk']-$v['otherpyrk']-$v['thrk']-$v['otherthrk']+$v['rkcj']+$v['otherrkcj']+$v['pkcj']+$v['otherpkcj']+$v['gqcj']+$v['othergqcj']+$v['qtcj']+$v['otherqtcj']+$v['ly']+$v['otherly']+$v['ll']+$v['otherll']+$v['fy']+$v['otherfy']-$v['cy']-$v['othercy']);

                    $mergeDatas[$k]['nowEnterStock']= intval($v['jh']+$v['qtrk']+$v['dbrk']+$v['pyrk']+$v['thrk']-$v['rkcj']-$v['pkcj']-$v['gqcj']-$v['qtcj']);
                    $mergeDatas[$k]['nowOutStock']= intval($v['ly']+$v['ll']+$v['fy']-$v['cy']);
                    
                    $mergeDatas[$k]['nowEnterCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['jhallcost'], $v['qtrkallcost'],4), $v['dbrkallcost'],4),  $v['pyrkallcost'],4),  $v['thrkallcost'],4),  -$v['rkcjallcost'],4),  -$v['pkcjallcost'],4),  -$v['gqcjallcost'],4),  -$v['qtcjallcost'],4);
                    $mergeDatas[$k]['nowOutCost'] = bcadd(bcadd(bcadd($v['lyallcost'],$v['llallcost'],4),  $v['fyallcost'],4),  -$v['cyallcost'],4);


                    $mergeDatas[$k]['beginCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['jhallcost'],4), -$v['otherjhallcost'],4), -$v['qtrkallcost'],4), -$v['otherqtrkallcost'],4), -$v['dbrkallcost'],4), -$v['otherdbrkallcost'],4), -$v['pyrkallcost'],4), -$v['otherpyrkallcost'],4), -$v['thrkallcost'],4), -$v['otherthrkallcost'],4), $v['rkcjallcost'],4), $v['otherrkcjallcost'],4), $v['pkcjallcost'],4), $v['otherpkcjallcost'],4), $v['gqcjallcost'],4), $v['othergqcjallcost'],4), $v['qtcjallcost'],4), $v['otherqtcjallcost'],4), $v['lyallcost'],4), $v['otherlyallcost'],4), $v['llallcost'],4), $v['otherllallcost'],4), $v['fyallcost'],4), $v['otherfyallcost'],4), -$v['cyallcost'],4), -$v['othercyallcost'],4);

                    

                    // $mergeDatas[$k]['fristPrice'] = intval(100 * ($v['allSurplusPrice'] - $v['jhallprice'] - $v['otherjhallprice'] - $v['qtrkallprice'] - $v['otherqtrkallprice'] - $v['dbrkallprice'] - $v['otherdbrkallprice'] - $v['pyrkallprice'] - $v['otherpyrkallprice'] - $v['thrkallprice'] - $v['otherthrkallprice'] + $v['rkcjallprice'] + $v['otherrkcjallprice'] + $v['pkcjallprice'] + $v['otherpkcjallprice'] + $v['gqcjallprice'] + $v['othergqcjallprice'] + $v['qtcjallprice'] + $v['otherqtcjallprice'] + $v['lyallprice'] + $v['otherlyallprice'] + $v['llallprice'] + $v['otherllallprice'])) / 100;
                    $mergeDatas[$k]['beginPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['jhallprice'],2), -$v['otherjhallprice'],2), -$v['qtrkallprice'],2), -$v['otherqtrkallprice'],2), -$v['dbrkallprice'],2), -$v['otherdbrkallprice'],2), -$v['pyrkallprice'],2), -$v['otherpyrkallprice'],2), -$v['thrkallprice'],2), -$v['otherthrkallprice'],2), $v['rkcjallprice'],2), $v['otherrkcjallprice'],2), $v['pkcjallprice'],2), $v['otherpkcjallprice'],2), $v['gqcjallprice'],2), $v['othergqcjallprice'],2), $v['qtcjallprice'],2), $v['otherqtcjallprice'],2), $v['lyallprice'],2), $v['otherlyallprice'],2), $v['llallprice'],2), $v['otherllallprice'],2), $v['fyallprice'],2), $v['otherfyallprice'],2), -$v['cyallprice'],2), -$v['othercyallprice'],2);



                    $mergeDatas[$k]['endStock'] = intval($v['pro_stock']-$v['otherjh']-$v['otherqtrk']-$v['otherdbrk']-$v['otherpyrk']-$v['otherthrk']+$v['otherrkcj']+$v['otherpkcj']+$v['othergqcj']+$v['otherqtcj']+$v['otherly']+$v['otherll']+$v['otherfy']-$v['othercy']);
                    // intval($v['pro_stock']-$v['otherjh']-$v['otherqtrk']-$v['otherdbrk']-$v['otherpyrk']-$v['otherthrk']+$v['otherrkcj']+$v['otherpkcj']+$v['othergqcj']+$v['otherqtcj']+$v['otherly']+$v['otherll']+$v['otherfy']-$v['othercy']);


                    $mergeDatas[$k]['endCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['otherjhallcost'],4), -$v['otherqtrkallcost'],4), -$v['otherdbrkallcost'],4),  -$v['otherpyrkallcost'],4),  -$v['otherthrkallcost'],4), $v['otherrkcjallcost'],4), $v['otherpkcjallcost'],4), $v['othergqcjallcost'],4), $v['otherqtcjallcost'],4), $v['otherlyallcost'],4), $v['otherllallcost'],4), $v['otherfyallcost'],4), -$v['othercyallcost'],4);

                    $mergeDatas[$k]['endPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['otherjhallprice'],2), -$v['otherqtrkallprice'],2), -$v['otherdbrkallprice'],2), -$v['otherpyrkallprice'],2), -$v['otherthrkallprice'],2), $v['otherrkcjallprice'],2), $v['otherpkcjallprice'],2), $v['othergqcjallprice'],2), $v['otherqtcjallprice'],2), $v['otherlyallprice'],2), $v['otherllallprice'],2), $v['otherfyallprice'],2), -$v['othercyallprice'],2);
                    // floor
                    // $fristCost = round($fristCost * 100)/ 100;
                    // var_dump($v);
                }
                 // var_dump(bcadd($left=1.0321456, $right=0.0243456, 7));
                


            }elseif($mergeData && empty($mergeOtherData)){
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                
                unset($mergeData);

                $mergeDatas = model('Manifest')->stockTypeDeal($mergeDatas);
                foreach ($mergeDatas as $k => $v) {
                    $surplusProData[$k] = db('project')->alias('p')
                                        ->field('p.pro_id, p.pro_cat1, l.lot_id, l.lcost, l.lprice, l.lstock')
                                        ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                                        ->where('l.lstock','>','0')
                                        ->where('p.pro_id',$k)
                                        ->select();

                }
                foreach ($surplusProData as $key => $value) {

                    $mergeDatas[$key]['allSurplusCost'] =0;
                    $mergeDatas[$key]['allSurplusPrice'] =0;
                    foreach ($value as $k => $va) {
                        $mergeDatas[$key]['allSurplusCost'] += $va['lcost']*$va['lstock'];
                        $mergeDatas[$key]['allSurplusPrice'] += $va['lprice']*$va['lstock'];
                    }
                    
                }

                foreach($mergeDatas as $k => $v){

                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['qtrk']-$v['dbrk']-$v['pyrk']-$v['thrk']+$v['rkcj']+$v['pkcj']+$v['gqcj']+$v['qtcj']+$v['ly']+$v['ll']+$v['fy']-$v['cy']);

                    

                    $mergeDatas[$k]['beginCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusCost'], -$v['jhallcost'],4), -$v['qtrkallcost'],4), -$v['dbrkallcost'],4),  -$v['pyrkallcost'],4),  -$v['thrkallcost'],4),  $v['rkcjallcost'],4),  $v['pkcjallcost'],4),  $v['gqcjallcost'],4),  $v['qtcjallcost'],4),  $v['lyallcost'],4),  $v['llallcost'],4),  $v['fyallcost'],4),  -$v['cyallcost'],4);

                    $mergeDatas[$k]['nowEnterStock']= intval($v['jh']+$v['qtrk']+$v['dbrk']+$v['pyrk']+$v['thrk']-$v['rkcj']-$v['pkcj']-$v['gqcj']-$v['qtcj']);
                    $mergeDatas[$k]['nowOutStock']= intval($v['ly']+$v['ll']+$v['fy']-$v['cy']);

                    $mergeDatas[$k]['nowEnterCost'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['jhallcost'], $v['qtrkallcost'],4), $v['dbrkallcost'],4),  $v['pyrkallcost'],4),  $v['thrkallcost'],4),  -$v['rkcjallcost'],4),  -$v['pkcjallcost'],4),  -$v['gqcjallcost'],4),  -$v['qtcjallcost'],4);
                    $mergeDatas[$k]['nowOutCost'] = bcadd(bcadd(bcadd($v['lyallcost'],$v['llallcost'],4),  $v['fyallcost'],4),  -$v['cyallcost'],4);

                    $mergeDatas[$k]['beginPrice'] = bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd(bcadd($v['allSurplusPrice'], -$v['jhallprice'],2),  -$v['qtrkallprice'],2),  -$v['dbrkallprice'],2),  -$v['pyrkallprice'],2),  -$v['thrkallprice'],2),  $v['rkcjallprice'],2),  $v['pkcjallprice'],2),  $v['gqcjallprice'],2),  $v['qtcjallprice'],2),  $v['lyallprice'],2),  $v['llallprice'],2),  $v['fyallprice'],2),  -$v['cyallprice'],2);

                    $mergeDatas[$k]['endStock'] = $v['pro_stock'];
                    $mergeDatas[$k]['endCost'] = $v['allSurplusCost'];
                    $mergeDatas[$k]['endPrice'] = $v['allSurplusPrice'];
                }

            }
            
            //产品类别
            $subjectLists = model('Pducat')->where(['pdc_zpttype'=> '4','pdc_status'=> '1','pdc_pid' => '0'])->column('pdc_id,pdc_name');
            
            foreach ($mergeDatas as $k => $v) {
                $mergeDatas[$k]['pro_cat1'] = isset($subjectLists[$v['pro_cat1']]) ? $subjectLists[$v['pro_cat1']] : '';
                $allBeginCost[] = $v['beginCost'];
                // $allBeginPrice[] = $v['beginPrice'];
                $allEndCost[] = $v['endCost'];
                // $allEndPrice[] = $v['endPrice'];

                // $allEnterStock[] = $v['nowEnterStock'];
                $allEnterCost[] = $v['nowEnterCost'];
                // $allOutStock[] = $v['nowOutStock'];
                $allOutCost[] = $v['nowOutCost'];
            }
            $total =[];
            if(!empty($allBeginCost)){
                $total['beginCost'] =array_sum($allBeginCost);
            }
            /*if(!empty($allBeginPrice)){
                $total['beginPrice'] =array_sum($allBeginPrice);
            }*/
            if(!empty($allEndCost)){
                $total['endCost'] =array_sum($allEndCost);
            }
            // if(!empty($allEndPrice)){
            //     $total['endPrice'] =array_sum($allEndPrice);
            // }

            /*if(!empty($allEnterStock)){
                $total['enterStock'] =array_sum($allEnterStock);
            }*/
            if(!empty($allEnterCost)){
                $total['enterCost'] =array_sum($allEnterCost);
            }
            /*if(!empty($allOutStock)){
                $total['outStock'] =array_sum($allOutStock);
            }*/
            if(!empty($allOutCost)){
                $total['outCost'] =array_sum($allOutCost);
            }
            
            
            //var_dump($total);//die();

            //var_dump($mergeDatas);//die();
                
            $this->view->assign('data',$mergeDatas);
            $this->view->assign('total',$total);

            $this->view->assign('where', json_encode($wheres));

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

        return $this->commondownloadprocess('stocksurplus', 'Stocksurplus name', $whereAddon);
    }



}