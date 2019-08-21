<?php

namespace app\admin\controller;

use app\admin\model\Msgtype;
use app\admin\model\Report;
use app\common\controller\Backend;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $request    = \think\Request::instance();
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist    = $createlist    = [];
        for ($i = 0; $i < 7; $i++) {
            $day              = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day]    = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $this->view->assign([
            'totaluser'          => 35200,
            'totalviews'         => 219390,
            'totalorder'         => 32143,
            'totalorderamount'   => 174800,
            'todayuserlogin'     => 321,
            'todayusersignup'    => 430,
            'todayorder'         => 2324,
            'todayunsettleorder' => 132,
            'sevendnu'           => '80%',
            'sevendau'           => '32%',
            'paylist'            => $paylist,
            'createlist'         => $createlist,
        ]);

        $this->view->assign('msgTypeList', Msgtype::getList());

        //当天医院统计
        $todaystartDate = date("Y-m-d");
        $todayendDate   = date('Y-m-d 23:59:59');
        $todaystartTime = strtotime($todaystartDate);
        $todayendTime   = strtotime($todayendDate);

        $today_where                = [];
        $today_where['createtime']  = ['BETWEEN', [$todaystartTime, $todayendTime]];
        $today_bwhere['updatetime'] = ['BETWEEN', [$todaystartTime, $todayendTime]];

        //营收总额
        $today_extraWhere['order_items.item_paytime'] = ['BETWEEN', [$todaystartTime, $todayendTime]];
        $today_pay_total                              = Report::getOrderItemsDetailCntNdSummary2([], $today_extraWhere);
        //划扣总金额
        $today_deduct_amount = model('DeductRecords')->where($today_where)
            ->column('SUM(deduct_amount ) AS deduct_amount');
        //订购项目金额
        $today_extraWhere['order_items.item_paytime'] = ['BETWEEN', [$todaystartTime, $todayendTime]];
        $today_order_total                            = Report::getOrderItemsDetailCntNdSummary2([], $today_extraWhere);

        $today_deposit_where['change_time'] = ['BETWEEN', [$todaystartTime, $todayendTime]];
        //定金总额
        $today_ctm_depositamt = model('AccountLog')->where($today_deposit_where)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动额(总增加)
        $today_total = model('AccountLog')->where($today_deposit_where)
            ->where('deposit_amt', 'gt', 0)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动额(总减少)
        $today_deposit_total = model('AccountLog')->where($today_deposit_where)
            ->where('deposit_amt', 'lt', 0)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动总额
        $today_change_total = $today_total[0] + $today_deposit_total[0];
        //分诊人次
        $today_coc = model('Customerosconsult')->where($today_where)
            ->column('count(osc_id) AS osc_id');
        //网电人次
        $today_cst = model('Customerconsult')->where($today_where)
            ->column('count(cst_id) AS cst_id');
        //回访次数(已回访/未回访)
        $today_rvinfoAll = model('rvinfo')->where(['rv_date' => ['BETWEEN', [$todaystartDate, $todaystartDate]]])
            ->column('count(rvi_id) AS rvi_id');
        //回访次数已回访
        $today_rvinfo = model('rvinfo')->where(['rv_time' => ['BETWEEN', [$todaystartTime, $todayendTime]]])
            ->column('count(rvi_id) AS rvi_id');
        //回访次数未回访
        $today_rvinfoNo = $today_rvinfoAll[0] - $today_rvinfo[0];

        $this->view->assign('today_pay_total', $today_pay_total['item_pay_total']);
        $this->view->assign('today_deduct_amount', $today_deduct_amount[0]);
        $this->view->assign('today_order_total', $today_order_total['item_original_pay_total']);
        $this->view->assign('today_ctm_depositamt', $today_ctm_depositamt[0]);
        $this->view->assign('today_total', $today_total[0]);
        $this->view->assign('today_deposit_total', $today_deposit_total[0]);
        $this->view->assign('today_change_total', $today_change_total[0]);
        $this->view->assign('today_coc', $today_coc[0]);
        $this->view->assign('today_cst', $today_cst[0]);
        $this->view->assign('today_rvinfoAll', $today_rvinfoAll[0]);
        $this->view->assign('today_rvinfo', $today_rvinfo[0]);
        $this->view->assign('today_rvinfoNo', $today_rvinfoNo);

        //本月医院统计
        $startDate = date('Y-m-01', strtotime(date('Y-m-01')));
        $endDate   = date('Y-m-d', strtotime("$startDate + 1 month -1 day"));
        $startTime = strtotime($startDate);
        $endTime   = strtotime($endDate);

        $where                = [];
        $where['createtime']  = ['BETWEEN', [$startTime, $endTime]];
        $bwhere['updatetime'] = ['BETWEEN', [$startTime, $endTime]];

        //营收总额
        $extraWhere['order_items.item_paytime'] = ['BETWEEN', [$startTime, $endTime]];
        $pay_total                              = Report::getOrderItemsDetailCntNdSummary2([], $extraWhere);
        //划扣总金额
        $deduct_amount = model('DeductRecords')->where($where)
            ->column('SUM(deduct_amount ) AS deduct_amount');
        //订购项目金额
        $extraWhere['order_items.item_paytime'] = ['BETWEEN', [$startTime, $endTime]];
        $order_total                            = Report::getOrderItemsDetailCntNdSummary2([], $extraWhere);
        $deposit_where['change_time']           = ['BETWEEN', [$startTime, $endTime]];
        //定金总额
        $ctm_depositamt = model('AccountLog')->where($deposit_where)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动额(总增加)
        $total = model('AccountLog')->where($deposit_where)
            ->where('deposit_amt', 'gt', 0)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动额(总减少)
        $deposit_total = model('AccountLog')->where($deposit_where)
            ->where('deposit_amt', 'lt', 0)
            ->column('SUM(deposit_amt ) AS deposit_amt');
        //定金变动总额
        $change_total = $total[0] + $deposit_total[0];
        //分诊人次
        $coc = model('Customerosconsult')->where($where)
            ->column('count(osc_id) AS osc_id');
        //网电人次
        $cst = model('Customerconsult')->where($where)
            ->column('count(cst_id) AS cst_id');
        //回访次数(已回访/未回访)
        $rvinfoAll = model('rvinfo')->where(['rv_date' => ['BETWEEN', [$startDate, $endDate]]])
            ->column('count(rvi_id) AS rvi_id');
        //回访次数已回访
        $rvinfo = model('rvinfo')->where(['rv_time' => ['BETWEEN', [$startTime, $endTime]]])
            ->column('count(rvi_id) AS rvi_id');
        //回访次数未回访
        $rvinfoNo = $rvinfoAll[0] - $rvinfo[0];

        /*
         *          产品过期预警
         */
        $sixMonthsLater = strtotime("+6 month");
        $lists          = db('project')->alias('p')
            ->field('p.pro_id, p.pro_name, p.pro_code,  p.pro_spec, p.depot_id, d.name as dname, u.name as uname, lot.lot_id,lot.lotnum,lot.lstock,lot.lstime,lot.letime,lot.lproducer,sup.sup_name')
            ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
            ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
            ->join('yjy_wm_lotnum lot', 'p.pro_id = lot.lpro_id', 'LEFT')
            ->join('yjy_wm_manlist ml', 'lot.lot_id = ml.lotid', 'LEFT')
            ->join('yjy_wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
            ->join('yjy_wm_supplier sup', 'mf.msupplier_id = sup.sup_id', 'LEFT')
            ->whereIn('mf.mprimary_type', ['1', '2'], 'or')
            ->where('lot.lstock', '>', '0')
            ->where('lot.letime', '<', $sixMonthsLater)
            ->where('p.pro_type', 1)
            ->order('lot.lot_id', 'ASC')
            ->select();
        $list       = [];
        $counts     = [];
        $expiredNum = 0;
        foreach ($lists as $key => $v) {
            $list[$v['pro_id']][] = $v;
            if ($v['letime'] < time()) {
                $expiredNum++;
            }
        }
        foreach ($list as $ke => $va) {
            $counts[$ke] = count($va);
        }
        // $co = count($lists);
        // var_dump($list);var_dump($co);
        $this->view->assign('data', $list);
        $this->view->assign('expiredNum', $expiredNum);
        $this->view->assign('counts', $counts);

        $this->view->assign('pay_total', $pay_total['item_pay_total']);
        $this->view->assign('deduct_amount', $deduct_amount[0]);
        $this->view->assign('order_total', $order_total['item_original_pay_total']);
        $this->view->assign('ctm_depositamt', $ctm_depositamt[0]);
        $this->view->assign('total', $total[0]);
        $this->view->assign('deposit_total', $deposit_total[0]);
        $this->view->assign('change_total', $change_total[0]);
        $this->view->assign('coc', $coc[0]);
        $this->view->assign('cst', $cst[0]);
        $this->view->assign('rvinfoAll', $rvinfoAll[0]);
        $this->view->assign('rvinfo', $rvinfo[0]);
        $this->view->assign('rvinfoNo', $rvinfoNo);

        return $this->view->fetch();
    }

    public function wmindex()
    {
        /*
         *          产品过期预警
         */

        $depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
        $this->view->assign("depotList", $depotLists);

        $where = [];
        $where['p.pro_type'] = 1;

        if($this->request->isPost()){     
               
            $postData = $this->request->post();

            unset($where['p.pro_type']);
            
            if($postData['p_name']){
                $where['p.pro_name'] = array('like','%'.$postData['p_name'].'%');//产品名称
            }
            if($postData['lotnum'] != null){
                $where['lot.lotnum'] = $postData['lotnum'];       //批号
            }
            if($postData['depot_id'] !=null){
                $where['p.depot_id'] = $postData['depot_id'];//所属仓库
            }
        }

        $sixMonthsLater = strtotime("+6 month");
        $lists          = db('project')->alias('p')
            ->field('p.pro_id, p.pro_name, p.pro_code,  p.pro_spec, p.depot_id, d.name as dname, u.name as uname, lot.lot_id,lot.lotnum,lot.lstock,lot.lstime,lot.letime,lot.lproducer,sup.sup_name')
            ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
            ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
            ->join('yjy_wm_lotnum lot', 'p.pro_id = lot.lpro_id', 'LEFT')
            ->join('yjy_wm_manlist ml', 'lot.lot_id = ml.lotid', 'LEFT')
            ->join('yjy_wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
            ->join('yjy_wm_supplier sup', 'mf.msupplier_id = sup.sup_id', 'LEFT')
            ->whereIn('mf.mprimary_type', ['1', '2'], 'or')
            ->where('lot.lstock', '>', '0')
            ->where('p.pro_type', '<>',9)
            ->where('lot.letime', '<', $sixMonthsLater)
            ->where($where)
            ->order('lot.lot_id', 'ASC')
            ->select();
        $list       = [];
        $counts     = [];
        $expiredNum = 0;
        foreach ($lists as $key => $v) {
            $list[$v['pro_id']][] = $v;
            if ($v['letime'] < time()) {
                $expiredNum++;
            }
        }
        foreach ($list as $ke => $va) {
            $counts[$ke] = count($va);
        }
        // $co = count($lists);
        // var_dump($list);var_dump($co);
        $this->view->assign('data', $list);
        $this->view->assign('expiredNum', $expiredNum);
        $this->view->assign('counts', $counts);

        return $this->view->fetch();
    }
}
