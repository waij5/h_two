<?php

namespace app\admin\controller\stat;

use app\admin\model\Prostat;
use app\common\controller\Backend;
use think\Controller;
use think\Request;
use app\admin\model\Project;

/**
 * 营收日结管理
 *
 * @icon fa fa-circle-o
 */
class Dailystat extends Backend
{

    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DailyStat');


    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $startDate = input('stat_date_start', date('Y-m-d', strtotime('-30 days', time())));
        $endDate   = input('stat_date_end', date('Y-m-d'));
        $where     = ['status' => 1, 'stat_date' => ['BETWEEN', [$startDate, $endDate]]];

        $sort  = 'stat_date';
        $order = 'ASC';

        $total = $this->model
            ->where($where)
            ->order($sort, $order)
            ->count();

        $list = $this->model
            ->where($where)
            ->order($sort, $order)
        // ->limit($offset, $limit)
            ->select();

        $summary = $this->model
            ->field('SUM(pay_total) AS pay_total,SUM(deposit_total) AS deposit_total,SUM(in_pay_total) AS in_pay_total,SUM(out_pay_total) AS out_pay_total,SUM(coupon_cost) AS coupon_cost,SUM(coupon_total) AS coupon_total, SUM(in_cash_pay_total) AS in_cash_pay_total,SUM(in_card_pay_total) AS in_card_pay_total,SUM(in_wechatpay_pay_total) AS in_wechatpay_pay_total,SUM(in_alipay_pay_total) AS in_alipay_pay_total,SUM(in_other_pay_total) AS in_other_pay_total,SUM(out_cash_pay_total) AS out_cash_pay_total,SUM(out_card_pay_total) AS out_card_pay_total,SUM(out_wechatpay_pay_total) AS out_wechatpay_pay_total,SUM(out_alipay_pay_total) AS out_alipay_pay_total,SUM(out_other_pay_total) AS out_other_pay_total,SUM(cash_pay_total) AS cash_pay_total,SUM(card_pay_total) AS card_pay_total,SUM(wechatpay_pay_total) AS wechatpay_pay_total,SUM(alipay_pay_total) AS alipay_pay_total,SUM(other_pay_total) AS other_pay_total, SUM(balance_count) AS balance_count')
            ->where($where)
            ->order($sort, $order)
            ->select();

        $result = array("total" => $total, "rows" => $list);

        $this->view->assign('result', $result);
        $this->view->assign('summary', $summary[0]);
        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);

        return $this->view->fetch();
    }

    /**
     *  订单项 分类目 统计
     * 默认剔除 未支付， 总次数为0（即全退的项目）
     */
    public function prostat2()
    {
        $lists = model('pducat')->where(['pdc_pid' => 0,'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('lists',$lists);

        $typeList = Project::getTypeList();
        $this->view->assign('typeList',$typeList);

        //输入的查询条件
        $startDate = input('item_paytime_start', date('Y-m-d', strtotime('-30 days', time())));
        $endDate   = input('item_paytime_end', date('Y-m-d'));
        $this->view->assign('startDate',$startDate);
        $this->view->assign('endDate',$endDate);

        $pro_cat1 = input('pro_cat1', 0);
        $pro_cat2 = input('pro_cat2', 0);
        $item_type = input('item_type');

        $start =  strtotime($startDate);
        $end =  strtotime($endDate);

        $where = ['order_items.item_paytime' => ['BETWEEN', [$start, $end]]];

        if(!empty($pro_cat1)){
            $where['project.pro_cat1'] = $pro_cat1;
        }
        if(!empty($pro_cat2)){
            $where['project.pro_cat2'] = $pro_cat2;
        }
        if(!empty($item_type)){
            $where['order_items.item_type'] = $item_type;
        }

        $this->view->assign('item_type',$item_type);
        $this->view->assign('pro_cat1',$pro_cat1);
        $this->view->assign('pro_cat2',$pro_cat2);
     
        $summary = model('admin/OrderItems')
            ->alias('order_items')
            ->join(model('admin/Project')->getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where([
                'item_paytime'     => ['gt', 0],
                'item_total_times' => ['gt', 0],
            ])
            ->where($where)
            ->limit(0, 1)
            ->column('SUM(item_qty) AS item_qty, COUNT(*) AS item_book_count, SUM(item_total_times) AS item_total_times, SUM(item_used_times) AS item_used_times, SUM(item_discount_total) AS item_discount_total,SUM(item_local_total) AS item_local_total, SUM(item_ori_total) AS item_ori_total, SUM(item_min_total) AS item_min_total, SUM(item_total) AS item_total, SUM(item_pay_total) AS item_pay_total, SUM(item_original_pay_total) AS item_original_pay_total');
        $summary = current($summary);

        $list = model('admin/OrderItems')
            ->alias('order_items')
            ->join(model('admin/Project')->getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where([
                'item_paytime'     => ['gt', 0],
                'item_total_times' => ['gt', 0],
            ])
            ->where($where)
            ->field('project.pro_cat1, project.pro_cat2, SUM(item_qty) AS item_qty,SUM(item_qty) AS item_qty, COUNT(*) AS item_book_count, SUM(item_total_times) AS item_total_times, SUM(item_used_times) AS item_used_times, SUM(item_discount_total) AS item_discount_total,SUM(item_local_total) AS item_local_total, SUM(item_ori_total) AS item_ori_total, SUM(item_min_total) AS item_min_total, SUM(item_total) AS item_total, SUM(item_pay_total) AS item_pay_total, SUM(item_original_pay_total) AS item_original_pay_total, order_items.pro_unit,order_items.pro_spec, order_items.dept_id, project.pro_cat1, project.pro_cat2')
            ->group('project.pro_cat1, project.pro_cat2')
            // , project.pro_cat3 , project.pro_cat3
            ->order('project.pro_cat1 ASC, project.pro_cat2', 'ASC')
            ->select();

        $catSummary = array();
        $pducatList = model('admin/Pducat')->column('pdc_name', 'pdc_id');
        foreach ($list as $key => $row) {
            $row['pro_cat1'] = intval($row['pro_cat1']);
            $row['pro_cat2'] = intval($row['pro_cat2']);
            // $row['pro_cat3'] = intval($row['pro_cat3']);

            if (!isset($catSummary[$row['pro_cat1']])) {
                //统计一级分类统计数据
                $catSummary[$row['pro_cat1']] = array(
                    'cat_name'                   => isset($pducatList[$row['pro_cat1']]) ? $pducatList[$row['pro_cat1']] : '其它',
                    'item_book_count'           => 0,

                    'item_qty'                   => 0,
                    'item_total'                 => 0,
                    'item_pay_total'             => 0,
                    'item_original_pay_total'    => 0,

                    'total_percent'          => 0.00,
                    'pay_total_percent'          => 0.00,
                    'original_pay_total_percent' => 0.00,

                    'item_used_times'            => 0,
                    'item_total_times'           => 0,
                    'subs'                       => [],
                );
            }

            $catSummary[$row['pro_cat1']]['item_book_count'] += $row['item_book_count'];

            $catSummary[$row['pro_cat1']]['item_qty'] += $row['item_qty'];
            $catSummary[$row['pro_cat1']]['item_total'] += $row['item_total'];
            $catSummary[$row['pro_cat1']]['item_pay_total'] += $row['item_pay_total'];
            $catSummary[$row['pro_cat1']]['item_original_pay_total'] += $row['item_original_pay_total'];

            //update percent
            if ($summary['item_total'] > 0) {
                $catSummary[$row['pro_cat1']]['total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['item_total'] / $summary['item_total']) / 100;
            }
            if ($summary['item_pay_total'] > 0) {
                $catSummary[$row['pro_cat1']]['pay_total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['item_pay_total'] / $summary['item_pay_total']) / 100;
            }
            if ($summary['item_original_pay_total'] > 0) {
                $catSummary[$row['pro_cat1']]['original_pay_total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['item_original_pay_total'] / $summary['item_original_pay_total']) / 100;
            }

            $catSummary[$row['pro_cat1']]['item_used_times'] += $row['item_used_times'];
            $catSummary[$row['pro_cat1']]['item_total_times'] += $row['item_total_times'];

            //统计二级分类统计数据
            if (!isset($catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']])) {
                $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']] = [
                    'cat_name'                   => isset($pducatList[$row['pro_cat2']]) ? $pducatList[$row['pro_cat2']] : '其它',
                    'item_book_count'           => 0,

                    'item_qty'                   => 0,
                    'item_total'                 => 0,
                    'item_pay_total'             => 0,
                    'item_original_pay_total'    => 0,

                    'total_percent'          => 0.00,
                    'pay_total_percent'          => 0.00,
                    'original_pay_total_percent' => 0.00,

                    'item_used_times'            => 0,
                    'item_total_times'           => 0,
                    'subs'                       => [],
                ];
            }

            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_book_count'] += $row['item_book_count'];

            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_qty'] += $row['item_qty'];
            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_total'] += $row['item_total'];
            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_pay_total'] += $row['item_pay_total'];
            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_original_pay_total'] += $row['item_original_pay_total'];

            //update percent
            if ($summary['item_total'] > 0) {
                $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_total'] / $summary['item_total']) / 100;
            }
            if ($summary['item_pay_total'] > 0) {
                $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['pay_total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_pay_total'] / $summary['item_pay_total']) / 100;
            }
            if ($summary['item_original_pay_total'] > 0) {
                $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['original_pay_total_percent'] = floor(10000 * $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_original_pay_total'] / $summary['item_original_pay_total']) / 100;
            }

            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_used_times'] += $row['item_used_times'];
            $catSummary[$row['pro_cat1']]['subs'][$row['pro_cat2']]['item_total_times'] += $row['item_total_times'];
        }

        $catListLv1 = model('Pducat')->where('pdc_pid', 0)->column('pdc_name', 'pdc_id');
        $catListLv2 = array();
        if (!empty($pro_cat1)) {
            $catListLv2 = model('Pducat')->where('pdc_pid', $pro_cat1)->column('pdc_name', 'pdc_id');
        }

        $this->view->assign('catListLv1', $catListLv1);
        $this->view->assign('catListLv2', $catListLv2);
        $this->assign('list', $catSummary);
        $this->assign('summary', $summary);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        return $this->commondownloadprocess('dailystat', 'Daily stat');
    }

    private function prostat($proType = 'project')
    {
        $startDate = input('stat_date_start', date('Y-m-d', strtotime('-30 days', time())));
        $endDate   = input('stat_date_end', date('Y-m-d'));

        if ($proType == 'project') {
            $type          = 'project';
            $catList       = model('Pducat')->order('pdc_pid ASC, pdc_sort', 'ASC')->column('pdc_pid AS pid, pdc_name AS name', 'pdc_id');
            $typeCondition = ['item_type' => 'project'];
            $infoTitle     = __('project stat info');
        } else {
            $type = input('type', null);
            if ($type == 'product_1' || $type == 'product_2') {
                $typeCondition = ['item_type' => $type];
                $infoTitle     = __($type . ' stat info');
            } else {
                $typeCondition = ['item_type' => ['like', 'product%']];
                $infoTitle     = __('product/medicine stat info');
            }

            $catList = model('Protype')->order('pid', 'ASC')->column('pid, name', 'id');
        }

        $list = model('ProStat')
            ->field('pro_cat1, pro_cat2, pro_cat3, SUM(pstat_order_count) AS pstat_order_count, SUM(pstat_qty) AS pstat_qty, SUM(pstat_local_total) AS pstat_local_total, SUM(pstat_ori_total) AS pstat_ori_total, SUM(pstat_min_total) AS pstat_min_total, SUM(pstat_total) AS pstat_total')
            ->where(['stat_date' => ['BETWEEN', [$startDate, $endDate]]])
            ->where($typeCondition)
            ->group('pro_id')
            ->select();

        $result = Prostat::proDataStat($list, $catList);

        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);
        $this->view->assign('total', $result['total']);
        $this->view->assign('statData', $result['data']);
        $this->view->assign('infoTitle', $infoTitle);
        $this->view->assign('type', $type);
        $this->view->assign('jsonStatData', json_encode($result['data']));

        return $this->view->fetch('/stat/dailystat/deptconsumption');
    }
}
