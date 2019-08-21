<?php

namespace app\admin\model;

use app\admin\model\AccountLog;
use app\admin\model\BalanceType;
use app\admin\model\Customer;
use app\admin\model\CustomerBalance;
use app\admin\model\MonthCustomerStat;
use app\admin\model\MonthStatLog;
use app\admin\model\OrderItems;
use think\Model;

class DailyStat extends Model
{
    // 表名
    protected $name = 'daily_stat';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [

    ];

    const STAT_TYPE_ALL     = 256;
    const STAT_TYPE_BALANCE = 1;

    //记录分段获取统计
    const PIECE_COUNT = 1000;

    /**
     * 每日营收统计
     */
    public static function generateDailyStat($calcDate, $adminId, $force = false, $autoSave = true)
    {
        $result = ['error' => true, 'msg' => 'Error occcurs', 'data' => array()];
        if (!preg_match('/[\d]{4}\-[\d]{2}\-[\d]{2}/', $calcDate)) {
            $result['msg'] = 'generateDailyStat === Invalid date format' . $calcDate;
            return $result;
        } elseif ($calcDate == date('Y-m-d')) {
            //不统计当天的数据
            // return false;
        }

        $msgPre = 'generateDailyStat === ' . $calcDate . '
';

        $dailyStat = self::where(['stat_date' => $calcDate, 'status' => 1])->count();
        if ($force == false && $dailyStat > 0) {
            $result['error'] = false;
            $result['msg']   = $msgPre . 'nothing to do, data has been generated when not force mode';
            return $result;
        }

        model('admin/DailyStat')->save(['status' => 0], ['stat_date' => $calcDate, 'status' => 1]);

        // $subSql = model('admin/CustomerBalance')->where(['create_date' => $calcDate])->buildSql();
        //整体统计
        $totals = model('admin/CustomerBalance')
            ->where(['create_date' => $calcDate])
            ->limit(1)
            ->column('SUM(pay_total) AS pay_total, SUM(deposit_total) AS deposit_total, SUM(coupon_cost) AS coupon_cost, SUM(coupon_total) AS coupon_total, SUM(cash_pay_total) AS cash_pay_total, SUM(card_pay_total) AS card_pay_total, SUM(wechatpay_pay_total) AS wechatpay_pay_total, SUM(alipay_pay_total) AS alipay_pay_total, SUM(other_pay_total) AS other_pay_total, COUNT(*) AS balance_count');

        $total = current($totals);
        //NULL数据初始化为0
        foreach ($total as $key => $value) {
            if ($total[$key] == null) {
                $total[$key] = 0;
            }
        }
        $total['in_pay_total']            = 0.00;
        $total['out_pay_total']           = 0.00;
        $total['in_cash_pay_total']       = 0.00;
        $total['in_card_pay_total']       = 0.00;
        $total['in_wechatpay_pay_total']  = 0.00;
        $total['in_alipay_pay_total']     = 0.00;
        $total['in_other_pay_total']      = 0.00;
        $total['out_cash_pay_total']      = 0.00;
        $total['out_card_pay_total']      = 0.00;
        $total['out_wechatpay_pay_total'] = 0.00;
        $total['out_alipay_pay_total']    = 0.00;
        $total['out_other_pay_total']     = 0.00;

        //分类目统计
        $summary = model('admin/CustomerBalance')
            ->where(['create_date' => $calcDate])
            ->group('balance_type')
            ->field('balance_type, SUM(pay_total) AS pay_total, SUM(deposit_total) AS deposit_total, SUM(coupon_cost) AS coupon_cost, SUM(coupon_total) AS coupon_total, SUM(cash_pay_total) AS cash_pay_total, SUM(card_pay_total) AS card_pay_total, SUM(wechatpay_pay_total) AS wechatpay_pay_total, SUM(alipay_pay_total) AS alipay_pay_total, SUM(other_pay_total) AS other_pay_total, COUNT(*) AS balance_count')
            ->select();

        //初始化
        $balanceTypeList = BalanceType::getList();
        $statSummary     = [];
        foreach (array_keys($balanceTypeList) as $balanceType) {
            $statSummary[$balanceType] = [
                "pay_total"           => 0.00,
                "deposit_total"       => 0.00,
                "coupon_cost"         => 0.00,
                "coupon_total"        => 0.00,

                "cash_pay_total"      => 0.00,
                "card_pay_total"      => 0.00,
                "wechatpay_pay_total" => 0.00,
                "alipay_pay_total"    => 0.00,
                "other_pay_total"     => 0.00,
                "balance_count"       => 0,
            ];
        }

        //赋值
        foreach ($summary as $key => $row) {
            if (isset($statSummary[$row['balance_type']])) {
                //部分是加NULL
                $statSummary[$row['balance_type']]['pay_total'] += $row['pay_total'];
                $statSummary[$row['balance_type']]['deposit_total'] += $row['deposit_total'];
                $statSummary[$row['balance_type']]['coupon_cost'] += $row['coupon_cost'];
                $statSummary[$row['balance_type']]['coupon_total'] += $row['coupon_total'];

                $statSummary[$row['balance_type']]['cash_pay_total'] += $row['cash_pay_total'];
                $statSummary[$row['balance_type']]['card_pay_total'] += $row['card_pay_total'];
                $statSummary[$row['balance_type']]['wechatpay_pay_total'] += $row['wechatpay_pay_total'];
                $statSummary[$row['balance_type']]['alipay_pay_total'] += $row['alipay_pay_total'];
                $statSummary[$row['balance_type']]['other_pay_total'] += $row['other_pay_total'];

                $itemPayTotal = $row['pay_total'] == null ? 0.00 : $row['pay_total'];

                if ($row['balance_type'] > 0) {
                    $preName = 'in_';
                } else {
                    $preName = 'out_';
                }
                $total[$preName . 'pay_total'] += $itemPayTotal;
                $total[$preName . 'cash_pay_total'] += $row['cash_pay_total'];
                $total[$preName . 'card_pay_total'] += $row['card_pay_total'];
                $total[$preName . 'alipay_pay_total'] += $row['alipay_pay_total'];
                $total[$preName . 'wechatpay_pay_total'] += $row['wechatpay_pay_total'];
                $total[$preName . 'other_pay_total'] += $row['other_pay_total'];
            }
        }

        $total['stat_date'] = $calcDate;
        $total['sub_data']  = json_encode($statSummary, true);
        $total['admin_id']  = $adminId;

        if ($autoSave) {
            $instance = new static;
            $instance->save($total);
        } else {
            return $total;
        }

        $result['error'] = false;
        $result['msg']   = $msgPre . 'Completed!';
        return $result;
    }

    /*
     * 生成月结顾客统计信息
     */
    public static function generateMonthlyCustomerStat($startTime, $endTime = null)
    {
        if (is_null($endTime)) {
            $endTime = strtotime('-1 day', strtotime(date('Y-m-d 23:59:59')));
        }
        if ($startTime >= $endTime) {
            \think\Log::record('Monthly customer stat warning: startTime can\'t be less than or equal to endTime');
            return false;
        }

        $statMonth = date('Y-m', $endTime);

        $customerTotal = Customer::count();
        $batchLimit    = 500;
        $batchMax      = ceil($customerTotal / $batchLimit);

        //月度统计日志
        $monthStatLog                   = new MonthStatLog;
        $monthStatLog->month            = $statMonth;
        $monthStatLog->check_time_start = $startTime;
        $monthStatLog->check_time_end   = $endTime;
        $monthStatLog->save();

        for ($currentBatch = 1; $currentBatch <= $batchMax; $currentBatch++) {
            $batchOffset = ($currentBatch - 1) * $batchLimit;
            $customers   = Customer::order('ctm_id', 'ASC')->limit($batchOffset, $batchLimit)->column('ctm_id, ctm_name, ctm_depositamt, ctm_salamt, ctm_affiliate, ctm_rank_points, ctm_pay_points', 'ctm_id');
            $customerIds = array_keys($customers);
            //期内变动汇总
            $accountLogs = AccountLog::where([
                'customer_id' => ['in', $customerIds],
                'change_time' => ['between', [$startTime, $endTime]],
            ])
                ->group('customer_id')
                ->column('customer_id, SUM(deposit_amt) AS deposit_change, SUM(frozen_deposit_amt) AS frozen_deposit_change, SUM(rank_points) AS rank_points_change, SUM(pay_points) AS pay_points_change, SUM(affiliate_amt) AS affiliate_change ', 'customer_id');
            //期内划扣汇总
            $deductRecords = DeductRecords::alias('rec')
                ->join(OrderItems::getTable() . ' items', 'rec.order_item_id = items.item_id')
                ->where(
                    [
                        'items.customer_id' => ['in', $customerIds],
                        'rec.createtime'    => ['between', [$startTime, $endTime]],
                    ]
                )
                ->group('items.customer_id')
                ->column('SUM(deduct_times) AS deduct_times, SUM(deduct_amount) AS deducted_total, SUM(deduct_benefit_amount) AS deducted_benefit_total', 'customer_id');

            $columns   = "count(*) as count, sum(case when item_status='%s' then 0 else item_total end) as item_total, sum(item_pay_total) as item_pay_total, sum(case when item_status='%s' then 0 else item_coupon_total end) as item_coupon_total, sum(item_original_total) as item_original_total, sum(item_original_pay_total) as item_original_pay_total,sum(case when item_status='%s' then 0 else item_total_times end) as total_times, sum(case when item_status='%s' then 0 else item_used_times end) as used_total_times, sum(item_undeducted_total) as undeducted_total, sum(case when item_old_id <> 0 then item_original_pay_total else 0 end) as item_switch_total";
            $columns   = sprintf($columns, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK);
            $itemsList = OrderItems::alias('order_items')
                ->where([
                    'item_status'  => ['in', [OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]],
                    'customer_id'  => ['in', $customerIds],
                    'item_paytime' => ['between', [$startTime, $endTime]],
                ])
                ->group('customer_id')
                ->limit(1)
                ->column($columns, 'customer_id');

            //本期收款
            $balanceList = CustomerBalance::where(['customer_id' => ['in', $customerIds]])->group('customer_id')->column('sum(pay_total) as pay_total', 'customer_id');

            //历史总未划扣 -- 付款未完成的订单
            $undeducteds = OrderItems::where([
                'item_status' => ['eq', OrderItems::STATUS_PAYED],
                'customer_id' => ['in', $customerIds],
            ])
                ->group('customer_id')
                ->column('SUM(item_undeducted_total) AS undeducted_total', 'customer_id');
            foreach ($customers as $customerId => $customer) {
                try {
                    $customerStat                = new MonthCustomerStat;
                    $customerStat->customer_id   = $customerId;
                    $customerStat->customer_name = $customer['ctm_name'];
                    $customerStat->depositamt    = $customer['ctm_depositamt'];
                    $customerStat->rank_points   = $customer['ctm_rank_points'];
                    $customerStat->pay_points    = $customer['ctm_pay_points'];
                    $customerStat->affiliate     = $customer['ctm_affiliate'];
                    //未划扣金额
                    if (isset($undeducteds[$customerId])) {
                        $customerStat->undeducted_total = $undeducteds[$customerId];
                    } else {
                        $customerStat->undeducted_total = 0.00;
                    }
                    //未出账金额
                    $customerStat->not_out_total = $customer['ctm_depositamt'] + $customerStat->undeducted_total;
                    //划扣
                    if (isset($deductRecords[$customerId])) {
                        $customerStat->deducted_total         = $deductRecords[$customerId]['deducted_total'];
                        $customerStat->deducted_benefit_total = $deductRecords[$customerId]['deducted_benefit_total'];
                    } else {
                        $customerStat->deducted_total         = 0.00;
                        $customerStat->deducted_benefit_total = 0.00;
                    }
                    //资金变动
                    if (isset($accountLogs[$customerId])) {
                        $customerStat->deposit_change     = $accountLogs[$customerId]['deposit_change'];
                        $customerStat->rank_points_change = $accountLogs[$customerId]['rank_points_change'];
                        $customerStat->pay_points_change  = $accountLogs[$customerId]['pay_points_change'];
                        $customerStat->affiliate_change   = $accountLogs[$customerId]['affiliate_change'];
                    } else {
                        $customerStat->deposit_change     = 0.00;
                        $customerStat->rank_points_change = 0;
                        $customerStat->pay_points_change  = 0;
                        $customerStat->affiliate_change   = 0.00;
                    }
                    //订购项目
                    if (isset($itemsList[$customerId])) {
                        $customerStat->item_original_pay_total = $itemsList[$customerId]['item_original_pay_total'];
                        $customerStat->item_switch_total       = $itemsList[$customerId]['item_switch_total'];
                    } else {
                        $customerStat->item_original_pay_total = 0.00;
                        $customerStat->item_switch_total       = 0.00;
                    }
                    //收款 实际营收，实收 + 退款 【负数】
                    $customerStat->balance_total = isset($balanceList[$customerId]) ? $balanceList[$customerId] : 0.00;

                    $customerStat->stat_date  = date('Y-m', $endTime);
                    $customerStat->status     = 1;
                    $customerStat->createtime = time();

                    $customerStat->save();
                } catch (\think\Exception\PDOException $e) {
                    \think\Log::record('Monthly customer stat warning: ' . $e->getMessage());
                    continue;
                }
            }
            if ($currentBatch % 2 == 0) {
                \think\Log::record('Monthly customer stat info: sleep for 1 second(batchNo: ' . $currentBatch . ')');
                sleep(1);
            }
        }
        $monthStatLog->endtime = time();
        $monthStatLog->save();
    }
}
