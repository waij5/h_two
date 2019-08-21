<?php

namespace app\admin\model;

use think\Model;

class MonthCustomerStat extends Model
{
    // 表名
    protected $name = 'month_customer_stat';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    public static function getDataListCnt($statDateStart, $statDateEnd, $lastPeriod, $pureWhere)
    {
        return static::where($pureWhere)->where('stat_date', '=', $statDateEnd)->group('customer_id')->count();
    }

    public static function getDataList($statDateStart, $statDateEnd, $lastPeriod, $pureWhere, $sort, $order, $offset, $limit)
    {
        // ->where('stat_date', '>=', $statDateStart)
        $currentPeriodData =static::where($pureWhere)->where('stat_date', '=', $statDateEnd)->order($sort, $order)->limit($offset, $limit)->column('customer_name, stat_date,depositamt, deposit_inc, deposit_dec, deposit_change,  undeducted_total, not_out_total, deducted_total, deducted_benefit_total, rank_points, pay_points, rank_points_change, pay_points_change, affiliate, affiliate_change, item_original_pay_total, item_switch_total, balance_total', 'customer_id');

        $lastPeriodData = static::where(['customer_id' => ['in', array_keys($currentPeriodData)]])->where('stat_date', $lastPeriod)->order($sort, $order)->limit($offset, $limit)->column('depositamt AS last_depositamt, deposit_inc AS last_deposit_inc, deposit_dec AS last_deposit_dec, deposit_change AS last_deposit_change, undeducted_total AS last_undeducted_total, not_out_total AS last_not_out_total, deducted_total AS last_deducted_total, deducted_benefit_total AS last_deducted_benefit_total, rank_points AS last_rank_points, pay_points AS last_pay_points, rank_points_change AS last_rank_points_change, pay_points_change AS last_pay_points_change, affiliate AS last_affiliate, affiliate_change AS last_affiliate_change, item_original_pay_total AS last_item_original_pay_total, item_switch_total AS last_item_switch_total, balance_total AS last_balance_total', 'customer_id');

        $periodChangeData = static::where('stat_date', '>=', $statDateStart)->where('stat_date', '<=', $statDateEnd)->where(['customer_id' => ['in', array_keys($currentPeriodData)]])->group('customer_id')->limit($offset, $limit)->column('SUM(depositamt) AS depositamt, SUM(deposit_inc) AS deposit_inc, SUM(deposit_dec) AS deposit_dec, SUM(deposit_change) AS deposit_change, SUM(undeducted_total) AS undeducted_total,  SUM(not_out_total) AS not_out_total, SUM(deducted_total) AS deducted_total, SUM(deducted_benefit_total) AS deducted_benefit_total, SUM(rank_points) AS rank_points, SUM(pay_points) AS pay_points, SUM(rank_points_change) AS rank_points_change, SUM(pay_points_change) AS pay_points_change, SUM(affiliate) AS affiliate,  SUM(affiliate_change) AS affiliate_change, SUM(item_original_pay_total) AS item_original_pay_total, SUM(item_switch_total) AS period_item_switch_total, SUM(balance_total) AS period_balance_total', 'customer_id');

        $list = array();
        foreach ($currentPeriodData as $key => $row) {
            $last = isset($lastPeriodData[$row['customer_id']]) ? $lastPeriodData[$row['customer_id']] : array('last_depositamt' => 0.00, 'last_deposit_inc' => 0.00, 'last_deposit_dec' => 0.00, 'last_deposit_change' => 0.00, 'last_undeducted_total' => 0.00, 'last_not_out_total' => 0.00, 'last_deducted_total' => 0.00, 'last_deducted_benefit_total' => 0.00, 'last_rank_points' => 0, 'last_pay_points' => 0, 'last_rank_points_change' => 0, 'last_pay_points_change' => 0, 'last_affiliate' => 0.00, 'last_affiliate_change' => 0.00, 'last_item_original_pay_total' => 0.00, 'last_item_switch_total' => 0.00, 'last_balance_total' => 0.00);

            $period = isset($periodChangeData[$row['customer_id']]) ? $periodChangeData[$row['customer_id']] : array('depositamt' => 0.00, 'deposit_inc' => 0.00, 'deposit_dec' => 0.00, 'deposit_change' => 0.00, 'undeducted_total' => 0.00, 'not_out_total' => 0.00, 'deducted_total' => 0.00, 'deducted_benefit_total' => 0.00, 'rank_points' => 0, 'pay_points' => 0, 'rank_points_change' => 0, 'pay_points_change' => 0, 'affiliate' => 0.00, 'affiliate_change' => 0.00, 'item_original_pay_total' => 0.00, 'item_switch_total' => 0.00, 'period_balance_total' => 0.00);

            $list[] = array_merge($last, $row, $period);
        }
        unset($lastPeriodData);
        unset($currentPeriodData);
        unset($periodChangeData);

        return $list;
    }

    public static function getDataSummary($statDateStart, $statDateEnd, $lastPeriod, $pureWhere)
    {
        //期初
        //期末
        //阶段汇总
        $startSummary = MonthCustomerStat::where($pureWhere)->where('stat_date', '=', $lastPeriod)->field('SUM(depositamt) AS last_depositamt, SUM(undeducted_total) AS last_undeducted_total, SUM(not_out_total) AS last_not_out_total, SUM(deducted_total) AS last_deducted_total, SUM(deducted_benefit_total) AS last_deducted_benefit_total, SUM(rank_points) AS last_rank_points, SUM(pay_points) AS last_pay_points, SUM(affiliate) AS last_affiliate, SUM(item_original_pay_total) AS last_item_original_pay_total, SUM(item_switch_total) AS last_item_switch_total, SUM(balance_total) AS last_balance_total')->find();

        $endSummary = MonthCustomerStat::where($pureWhere)->where('stat_date', '=', $statDateEnd)->field('SUM(depositamt) AS depositamt, SUM(affiliate) AS affiliate, SUM(undeducted_total) AS undeducted_total, SUM(not_out_total) AS not_out_total, SUM(deducted_total) AS deducted_total, SUM(deducted_benefit_total) AS deducted_benefit_total, SUM(rank_points) AS rank_points, SUM(pay_points) AS pay_points, SUM(item_original_pay_total) AS item_original_pay_total, SUM(item_switch_total) AS item_switch_total, SUM(balance_total) AS balance_total')->find();

        $periodSummary = MonthCustomerStat::where('stat_date', '>=', $statDateStart)
            ->where('stat_date', '<=', $statDateEnd)->field('SUM(deposit_inc) AS deposit_inc, SUM(deposit_dec) AS deposit_dec, SUM(deposit_change) AS period_deposit_change, SUM(rank_points_change) AS period_rank_points_change, SUM(pay_points_change) AS period_pay_points_change, SUM(affiliate_change) AS period_affiliate_change, SUM(item_switch_total) AS period_item_switch_total, SUM(balance_total) AS period_balance_total')->find();

        $summary = array_merge($startSummary->getData(), $endSummary->getData(), $periodSummary->getData());
        $summary = array_map(function ($value) {
            return is_null($value) ? 0.00 : $value;
        }, $summary);

        return $summary;
    }

}
