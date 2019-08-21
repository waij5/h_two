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
class Recipe extends Backend
{
    /**
     * Unit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotLists = model('Depot')->where('status','normal')->where('type','1')->order('id', 'asc')->select();
        $this->view->assign("depotList", $depotLists);
    }

    public function index(){
        if($this->request->isPost()){
            $postData = $this->request->post();

            $where = [];
            if($postData !=null){
                if($postData['stime']!=null && $postData['etime']!=null){
                    $censusDate = '查询日期：'.$postData['stime'].' 至 '.$postData['etime'];
                    $this->view->assign('censusDate', $censusDate);
                    $where['sl.sltime'] = ['between',[strtotime($postData['stime'].'00:00:00'),strtotime($postData['etime'].'23:59:59')]];
                }

                if($postData['depot_id'] !=null){
                    $where['p.depot_id'] = $postData['depot_id'];
                }
                if($postData['p_num'] != null){
                    $where['p.pro_code'] = $postData['p_num'];
                }
                if($postData['p_name'] !=null){
                    $where['p.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
                }
            }
            $where['sl.sltype'] = ['between',[6,7]];
            $data = db('wm_stocklog')->alias('sl')
                    ->field('sl.slnum, sl.slallcost, sl.slallprice,sl.slcost, sl.sldr_id, sl.sltype, sl.sltime, l.lotnum, p.pro_id, p.pro_code, p.pro_name, p.pro_spec, u.name as uname, c.ctm_name')
                    ->join('yjy_wm_lotnum l', 'sl.slotid = l.lot_id', 'LEFT')
                    ->join('yjy_project p', 'l.lpro_id = p.pro_id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_customer c', 'sl.slcustomer_id = c.ctm_id', 'LEFT')
                    ->where($where)
                    ->select();
            $res =[];
            // $totalss=[];
            $totalss['fynum']=0;
            $totalss['fycost']=0;
            $totalss['cynum']=0;
            $totalss['cycost']=0;
            
            // foreach ($res as $ke => $val) {
                array_multisort(array_column($data, 'sltime'), SORT_DESC, $data);
            // }
            foreach ($data as $k => $v) {
                $res[$v['pro_id']][] = $v;
                if($v['sltype']==6){
                    $totalss['fynum'] += $v['slnum'];
                    $totalss['fycost'] += $v['slallcost'];
                }else{
                    $totalss['cynum'] += $v['slnum'];
                    $totalss['cycost'] += $v['slallcost'];
                }
                
            }
            $counts = [];
            foreach ($res as $key => $val) {
                $counts[$key] = count($val);

            }
                // var_dump($totalss);
            $this->view->assign('res',$res);
            $this->view->assign('totalss',$totalss);
            $this->view->assign('counts',$counts);
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

        return $this->commondownloadprocess('recipe', 'Recipe name', $whereAddon);
    }



}