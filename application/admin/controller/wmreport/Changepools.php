<?php

namespace app\admin\controller\wmreport;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 仓库盘点
 *
 * @icon fa fa-circle-o
 */
class Changepools extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotList = db('depot')->where('status','normal')->order('type', 'asc')->select();
        // $pducat = db('pducat')->where(['pdc_status'=>'1','pdc_zpttype'=>'4','pdc_pid' => '0'])->order('pdc_id', 'asc')->select();
        // $pducatsons = db('pducat')->where('pdc_pid > 0')->where(['pdc_status'=>'1','pdc_zpttype'=>'4'])->order('pdc_id', 'asc')->select();
		
        $this->view->assign("depotList", $depotList);
        // $this->view->assign("pducat", $pducat);
        // $this->view->assign("pducatsons", $pducatsons);
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

        	$nowDate = date('Y-m-d');
            $otherSlData='';
            if($nowDate > $postData['etime']){
                $otherWhere['sl.sltime'] = ['between',[strtotime($postData['etime'].'23:59:59')+1,strtotime($nowDate.'23:59:59')]];
                // $w['sl.sltime'] = ['between',[strtotime($postData['etime'].'23:59:59'),strtotime($nowDate.'23:59:59')]];

                $otherSlData = db('wm_stocklog')->alias('sl')
                                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                                ->where($otherWhere)
                                ->order('p.pro_id','ASC')
                                ->select();
                                
                $otherSlData = model('Manifest')->changepoolsArrDeal($otherSlData);

            }


            
//更新20190111
            /*$proData = db('project')->alias('p')
                        ->field('p.pro_id, p.pro_name, p.pro_code, p.pro_unit, p.pro_stock, p.pro_spec, u.name as uname')
                        ->join('yjy_wm_lotnum l', 'p.pro_id = l.lpro_id', 'LEFT')
                        ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                        ->where('p.pro_type', '<>','9')
                        ->where($where)
                        ->order('p.pro_id', 'DESC')
                        ->select();*/
//更新20190111

            $slData = db('wm_stocklog')->alias('sl')
                ->field('p.pro_id, p.pro_name, p.pro_stock, p.pro_code, p.pro_spec,sl.*, lot.lotnum, lot.lstock, lot.lcost, lot.lprice, u.name as uname')
                ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                ->where($wheres)
                ->order('p.pro_id','ASC')
                ->select();

            $datasss = model('Manifest')->changepoolsArrDeal($slData);

            // $proDatas =[];
            $mergeData =[];
            $mergeDatas =[];
            $mergeOtherData =[];
            $mergeOtherDatas =[];
            // $issetOtherData ='';   


//更新20190111              
            /*if($proData && $datasss){
                foreach ($proData as $key => $value) {
                    $proDatas[$value['pro_id']] = $value;
                    // $slDatas[$value['lot_id']] = 0;
                }

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


            }*/


//统计库存结余表全部金额  改为 统计日期内有变动的数据
            $mergeData = $datasss;
            $mergeOtherData = $otherSlData;
//更新20190111

// var_dump($nowDate);die();
            if($mergeData && $mergeOtherData){
                // $issetOtherData = '1';
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                foreach ($mergeOtherData as $k => $v) {
                    $mergeOtherDatas[$v['pro_id']] = $v;
                }
                
                $mergeOtherDatas = model('Manifest')->stockTypeDeal($mergeOtherDatas,'1');
                $mergeDatas = model('Manifest')->stockTypeDeal($mergeDatas,'1');

// var_dump($mergeOtherDatas);var_dump($postData);

                
                foreach($mergeDatas as $k => $v){           //将Other数据存入slData
//更新20190111
                    $mergeDatas[$k]['otherjh'] = isset($mergeOtherDatas[$k]['jh'])?$mergeOtherDatas[$k]['jh']:0;
                    $mergeDatas[$k]['otherqtrk'] = isset($mergeOtherDatas[$k]['qtrk'])?$mergeOtherDatas[$k]['qtrk']:0;

                    $mergeDatas[$k]['otherdbrk'] = isset($mergeOtherDatas[$k]['dbrk'])?$mergeOtherDatas[$k]['dbrk']:0;

                    $mergeDatas[$k]['otherpyrk'] = isset($mergeOtherDatas[$k]['pyrk'])?$mergeOtherDatas[$k]['pyrk']:0;

                    $mergeDatas[$k]['otherthrk'] = isset($mergeOtherDatas[$k]['thrk'])?$mergeOtherDatas[$k]['thrk']:0;

                    $mergeDatas[$k]['otherrkcj'] = isset($mergeOtherDatas[$k]['rkcj'])?$mergeOtherDatas[$k]['rkcj']:0;

                    $mergeDatas[$k]['otherpkcj'] = isset($mergeOtherDatas[$k]['pkcj'])?$mergeOtherDatas[$k]['pkcj']:0;

                    $mergeDatas[$k]['othergqcj'] = isset($mergeOtherDatas[$k]['gqcj'])?$mergeOtherDatas[$k]['gqcj']:0;

                    $mergeDatas[$k]['otherqtcj'] = isset($mergeOtherDatas[$k]['qtcj'])?$mergeOtherDatas[$k]['qtcj']:0;

                    $mergeDatas[$k]['otherly'] = isset($mergeOtherDatas[$k]['ly'])?$mergeOtherDatas[$k]['ly']:0;

                    $mergeDatas[$k]['otherll'] = isset($mergeOtherDatas[$k]['ll'])?$mergeOtherDatas[$k]['ll']:0;

                    $mergeDatas[$k]['otherfy'] = isset($mergeOtherDatas[$k]['fy'])?$mergeOtherDatas[$k]['fy']:0;

                    $mergeDatas[$k]['othercy'] = isset($mergeOtherDatas[$k]['cy'])?$mergeOtherDatas[$k]['cy']:0;

//更新20190111     
                }
                


                // $fristPrice = 0;
                foreach($mergeDatas as $k => $v){

                    // $mergeDatas[$k]['fristCost'] = intval(100 * ($v['allSurplusCost'] - $v['jhallcost'] - $v['otherjhallcost'] - $v['qtrkallcost'] - $v['otherqtrkallcost'] - $v['dbrkallcost'] - $v['otherdbrkallcost'] - $v['pyrkallcost'] - $v['otherpyrkallcost'] - $v['thrkallcost'] - $v['otherthrkallcost'] + $v['rkcjallcost'] + $v['otherrkcjallcost'] + $v['pkcjallcost'] + $v['otherpkcjallcost'] + $v['gqcjallcost'] + $v['othergqcjallcost'] + $v['qtcjallcost'] + $v['otherqtcjallcost']+ $v['lyallcost'] + $v['otherlyallcost'] + $v['llallcost'] + $v['otherllallcost'])) / 100;

                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['otherjh']-$v['qtrk']-$v['otherqtrk']-$v['dbrk']-$v['otherdbrk']-$v['pyrk']-$v['otherpyrk']-$v['thrk']-$v['otherthrk']+$v['rkcj']+$v['otherrkcj']+$v['pkcj']+$v['otherpkcj']+$v['gqcj']+$v['othergqcj']+$v['qtcj']+$v['otherqtcj']+$v['ly']+$v['otherly']+$v['ll']+$v['otherll']+$v['fy']+$v['otherfy']-$v['cy']-$v['othercy']);
                    


                    $mergeDatas[$k]['endStock'] = intval($v['pro_stock']-$v['otherjh']-$v['otherqtrk']-$v['otherdbrk']-$v['otherpyrk']-$v['otherthrk']+$v['otherrkcj']+$v['otherpkcj']+$v['othergqcj']+$v['otherqtcj']+$v['otherly']+$v['otherll']+$v['otherfy']-$v['othercy']);

                    
                    // var_dump($v);
                }
                 // var_dump(bcadd($left=1.0321456, $right=0.0243456, 7));
                


            }elseif($mergeData){
                foreach ($mergeData as $k => $v) {
                    $mergeDatas[$v['pro_id']] = $v;
                }
                $mergeDatas = model('Manifest')->stockTypeDeal($mergeDatas,'1');
               
                

                foreach($mergeDatas as $k => $v){

                    $mergeDatas[$k]['beginStock'] = intval($v['pro_stock']-$v['jh']-$v['qtrk']-$v['dbrk']-$v['pyrk']-$v['thrk']+$v['rkcj']+$v['pkcj']+$v['gqcj']+$v['qtcj']+$v['ly']+$v['ll']+$v['fy']-$v['cy']);

                    $mergeDatas[$k]['endStock'] = $v['pro_stock'];
                }

            }
            

            
            // var_dump($mergeDatas);die();
                
            $this->view->assign('data',$mergeDatas);
            // $this->view->assign('issetOtherData',$issetOtherData);

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

        return $this->commondownloadprocess('changepools', 'Changepools name', $whereAddon);
    }

}