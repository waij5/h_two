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
class Newstocksurplus extends Backend
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
        if($this->request->isAjax()){

            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            
            $filter = $this->request->get("filter", '');
            $postData = json_decode($filter, TRUE);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            // var_dump($postData);die();

            $nowHappenDate = [];
            $otherHappenDate = [];
            $wheres = $where;
            //更新20190111
            $postData['stime'] = !empty($postData['stime'])?$postData['stime']:'2017-01-01';
            $postData['etime'] = !empty($postData['etime'])?$postData['etime']:date('Y-m-d');
            //更新20190111
            if($postData['stime'] && $postData['etime']){
                $nowHappenDate['sl.sltime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
            }

            //本期数据
            $nowDateList = DB::table('yjy_project')->alias('p')
                            ->field('p.pro_id, p.pro_code, p.pro_name, p.pro_spec, p.pro_stock, pdu.pdc_name as one_type, pdus.pdc_name as two_type, u.name as uname, sl.slotid, lot.lotnum, sl.sltime, sl.sltype, sl.slcost, sl.slallcost, sl.slnum')
                            ->join('yjy_wm_lotnum lot', 'p.pro_id=lot.lpro_id', 'LEFT')
                            ->join('yjy_wm_stocklog sl', 'lot.lot_id=sl.slotid', 'LEFT')
                            ->join('yjy_pducat pdu', 'p.pro_cat1=pdu.pdc_id', 'LEFT')
                            ->join('yjy_pducat pdus', 'p.pro_cat2=pdus.pdc_id', 'LEFT')
                            ->join('yjy_unit u', 'p.pro_unit=u.id', 'LEFT')
                            ->where('p.pro_type','<>','9')
                            ->where($nowHappenDate)
                            ->where($where)
                            ->select();

            //所有产品数据
            $allProList = DB::table('yjy_project')->alias('p')
                            ->field('p.pro_id, p.pro_code, p.pro_name, p.pro_spec, p.pro_stock, lot.lstock*lot.lcost as lot_total')
                            ->join('yjy_wm_lotnum lot', 'p.pro_id=lot.lpro_id', 'LEFT')
                            // ->join('yjy_pducat pdu', 'p.pro_cat1=pdu.pdc_id', 'LEFT')
                            // ->join('yjy_pducat pdus', 'p.pro_cat2=pdus.pdc_id', 'LEFT')
                            // ->join('yjy_unit u', 'p.pro_unit=u.id', 'LEFT')
                            ->where('p.pro_type','<>','9')
                            ->where($where)
                            ->order($sort, $order)
                            // ->limit($offset, $limit)
                            // ->group('p.pro_id')
                            ->select();
            $limitTotal = DB::table('yjy_project')->alias('p')
                            ->where('p.pro_type','<>','9')
                            ->where($where)
                            ->count();

            $limitProList = DB::table('yjy_project')->alias('p')
                            ->field('p.pro_id, p.pro_code, p.pro_name, p.pro_spec, p.pro_stock, pdu.pdc_name as one_type, pdus.pdc_name as two_type, u.name as uname,lot.lstock*lot.lcost as lot_total')
                            ->join('yjy_wm_lotnum lot', 'p.pro_id=lot.lpro_id', 'LEFT')
                            ->join('yjy_pducat pdu', 'p.pro_cat1=pdu.pdc_id', 'LEFT')
                            ->join('yjy_pducat pdus', 'p.pro_cat2=pdus.pdc_id', 'LEFT')
                            ->join('yjy_unit u', 'p.pro_unit=u.id', 'LEFT')
                            ->where('p.pro_type','<>','9')
                            ->where($where)
                            ->order($sort, $order)
                            // ->limit($offset, $limit)针对pro不是lot
                            ->select();
                            // ->group('p.pro_id')
                            // ->column('p.pro_id, p.pro_code, p.pro_name, p.pro_spec, p.pro_stock, pdu.pdc_name as one_type, pdus.pdc_name as two_type, u.name as uname,lot.lstock*lot.lcost as lot_total');
            // $allProData = array_column(input, column_key)
            $allProData =[];
            $limitProData =[];
            $totalFinalCost =0;
            foreach ($limitProList as $k => $v) {
                $limitProData[$v['pro_id']]['pro_id'] = $v['pro_id'];
                $limitProData[$v['pro_id']]['pro_code'] = $v['pro_code'];
                $limitProData[$v['pro_id']]['pro_name'] = $v['pro_name'];
                $limitProData[$v['pro_id']]['pro_spec'] = $v['pro_spec'];
                $limitProData[$v['pro_id']]['pro_stock'] = $v['pro_stock'];
                $limitProData[$v['pro_id']]['one_type'] = $v['one_type'];
                $limitProData[$v['pro_id']]['two_type'] = $v['two_type'];
                $limitProData[$v['pro_id']]['uname'] = $v['uname'];
                $limitProData[$v['pro_id']]['lot_total'] = 0;
            }
var_dump($limitProList);
            foreach ($limitProList as $k => $v) {
                $limitProData[$v['pro_id']]['lot_total'] += $v['lot_total'];
            }

            /*foreach ($allProList as $k => $v) {
                if(in_array($v['pro_id'],$limitProList)){
                    $limitProData[$v['pro_id']]['lot_total'] = 0;
                }
                $allProData[$v['pro_id']]['lot_total'] = 0;
            }*/

            foreach ($allProList as $k => $v) {
                /*if(in_array($v['pro_id'],$limitProData)){
                    $limitProData[$v['pro_id']]['lot_total'] += $v['lot_total'];
                }*/
                $totalFinalCost += $v['lot_total'];
            }

            /*期末数据（etime--今天23:59:59）
              etime>=今天，则期末=当前库存，否则计算etime--今天23:59:59的数据
            */
            if($postData['etime']<date('Y-m-d')){
                $nowHappenDate['sl.sltime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
            }else{
                //本期所有变动的产品数据，变动的替换对应的数据
                //前端如果jh_num存在则jh_num，否则0
                $nowDateDealData = model('Newstocksurplus')->dealAllData($nowDateList,$limitProData,1);  
                $nowDateDealData['totalFinalCost'] = $totalFinalCost;  
                $nowDateDealData['totalFirstCost'] = $totalFinalCost+$nowDateDealData['totalNowOutCost']-$nowDateDealData['totalNowEnterCost'];       
                
            }
            foreach ($nowDateDealData['limitProData'] as $key => $v) {
                $limitData[] = $v;
            }
            // $allProData = $nowDateDealData['allProData'];
            // $allProData = array_merge_recursive($nowDateAllData,$allProData);
            
            /*foreach ($nowDateAllData as $key => $value) {
                foreach ($value as $ke => $val) {
                    if($ke==4){
                        // $nowDateDealData[$key]['ly_num'] = array_sum($val['slnum']);
                        $nowDateDealData = $val;
                    }
                }
            }*/

            
            
            // var_dump($nowDateDealData);
                     
            $result = array("total" => $limitTotal, "rows" => $limitProData);
            $this->view->assign('where', json_encode($wheress));
            return json($result);
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