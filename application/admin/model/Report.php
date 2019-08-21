<?php

namespace app\admin\model;

use app\admin\model\CProject;
use app\admin\model\Customer;
use app\admin\model\Ctmsource;
use app\admin\model\Ctmchannels;
use app\admin\model\CustomerBalance;
use app\admin\model\CustomerOsconsult;
use app\admin\model\OrderItems;
use think\Model;

//报表
class Report extends Model
{
    /**
     *  客户订购项目汇总表记录数
     * 已付款，已完成，退款单
     */
    public static function getCustomerOrderItemSummaryCount($where, $includeSummary = false)
    {
        if ($includeSummary) {
            $columns = "count(distinct order_items.customer_id) as count, count(order_items.item_id) as item_count, sum(order_items.item_total_times) as item_total_times, sum(order_items.item_used_times) as item_used_times, sum(case when item_status='%s' then 0 else item_total end) as item_total, sum(item_pay_total) as item_pay_total, sum(case when item_status='%s' then 0 else item_coupon_total end) as item_coupon_total, sum(item_original_total) as item_original_total, sum(item_original_pay_total) as item_original_pay_total,sum(case when item_status='%s' then 0 else item_total_times end) as total_times, sum(case when item_status='%s' then 0 else item_used_times end) as used_total_times, sum(item_undeducted_total) as undeducted_total, sum(case when item_old_id <> 0 then item_original_pay_total else 0 end) as item_switch_total";
            $columns = sprintf($columns, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK);

            return current(OrderItems::alias('order_items')
                ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
                ->where($where)
                ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED, OrderItems::STATUS_CHARGEBACK]]])
                ->limit(1)
                ->column($columns));
        } else {
            return OrderItems::alias('order_items')
                ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
                ->where($where)
                ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED, OrderItems::STATUS_CHARGEBACK]]])
                ->group('order_items.customer_id')
                ->count();
        }
    }

    /**
     * 客户订购项目汇总表记录
     * 订单数包含退款单(退换产生的额外单)
     * 退款单，次数为0，item_pay_total为0
     */
    public static function getCustomerOrderItemSummary($where, $offset = 0, $limit = null)
    {
        //注意，dbalance是顾客定金变动
        //+增加（订单金额减小） -减少（订单金额增加）
        $extraColumn = ", sum(case when item_status='%s' then 0 else item_total end) as item_total, sum(item_pay_total) as item_pay_total, sum(case when item_status='%s' then 0 else item_coupon_total end) as item_coupon_total, sum(item_original_total) as item_original_total, sum(item_original_pay_total) as item_original_pay_total,sum(case when item_status='%s' then 0 else item_total_times end) as total_times, sum(case when item_status='%s' then 0 else item_used_times end) as used_total_times, sum(item_undeducted_total) as undeducted_total, sum(case when item_old_id <> 0 then item_original_pay_total else 0 end) as item_switch_total";
        $extraColumn = sprintf($extraColumn, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK);

        $list = OrderItems::alias('order_items')
            ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(Admin::getTable() . ' admin', 'customer.admin_id = admin.id', 'LEFT')
            ->group('customer.ctm_id')
            ->order('customer.ctm_id', 'ASC')
            ->where($where)
            ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED, OrderItems::STATUS_CHARGEBACK]]])
            ->limit($offset, $limit)
            ->column('order_items.customer_id, customer.ctm_name, customer.admin_id AS develop_admin_id, admin.nickname AS  develop_admin_name, customer.ctm_depositamt, COUNT(order_items.item_id) AS item_count, SUM(order_items.item_total_times) AS item_total_times, SUM(order_items.item_used_times) AS item_used_times' . $extraColumn);

        return $list;
    }

    /**
     *  客户订购项目汇总表记录2
     */
    public static function getOrderItemsDetail2($where, $offset, $limit, $extraWhere)
    {
        //只选择 退款单，支付单， 已完成单
        $list = OrderItems::alias('order_items')->join(CustomerOsconsult::getTable() . ' osc', 'order_items.osconsult_id = osc.osc_id', 'LEFT')
            ->join(CProject::getTable() . ' c_project', 'osc.cpdt_id = c_project.id', 'LEFT')
            ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(Ctmsource::getTable() . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Ctmchannels::getTable() . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->join(\app\admin\model\Project::getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]]])
            ->where($where)
            ->where($extraWhere)
            ->field('osc.osc_type, osc.dept_id as osc_dept_id, osc.cpdt_id as osc_cpdt_id, customer.ctm_name, customer.admin_id AS develop_admin_id, customer.ctm_first_tool_id, customer.ctm_first_recept_time, customer.ctm_first_dept_id, customer.ctm_first_cpdt_id, sce.sce_name as ctm_source, channels.chn_name as ctm_explore, order_items.admin_id as osconsult_admin_id, order_items.*')
            ->order('order_items.item_createtime DESC, order_items.item_id', 'DESC')
            ->limit($offset, $limit)
            ->select();

        return $list;
    }

    /**
     *  客户订购项目汇总表记录
     */
    public static function getOrderItemsDetailCntNdSummary2($where, $extraWhere, $includeSummary = true)
    {
        if ($includeSummary) {
            $columns = "count(*) as count, COUNT(distinct order_items.customer_id) as uniq_customer_count, sum(case when item_status='%s' then 0 else item_total end) as item_total, sum(item_pay_total) as item_pay_total, sum(case when item_status='%s' then 0 else item_coupon_total end) as item_coupon_total, sum(item_original_total) as item_original_total, sum(item_original_pay_total) as item_original_pay_total,sum(case when item_status='%s' then 0 else item_total_times end) as total_times, sum(case when item_status='%s' then 0 else item_used_times end) as used_total_times, sum(item_undeducted_total) as undeducted_total";
            $columns = sprintf($columns, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK);
        } else {
            $columns = 'count(*) as count';
        }

        $summarys = OrderItems::alias('order_items')->join(CustomerOsconsult::getTable() . ' osc', 'order_items.osconsult_id = osc.osc_id', 'LEFT')
            ->join(CProject::getTable() . ' c_project', 'osc.cpdt_id = c_project.id', 'LEFT')
            ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(Ctmsource::getTable() . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Ctmchannels::getTable() . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->join(\app\admin\model\Project::getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]]])
            ->where($where)
            ->where($extraWhere)
            ->limit(1)
            ->column($columns);

        $summary = current($summarys);

        if (is_array($summary)) {
            foreach ($summary as $key => $value) {
                if (is_null($value)) {
                    $summary[$key] = 0.00;
                } else {
                    $summary[$key] = floor($value * 100) / 100;
                }
            }
            $summary['deducted_total'] = $summary['item_pay_total'] - $summary['undeducted_total'];
        } else {
            $count            = $summary;
            $summary          = array();
            $summary['count'] = $count;
        }

        return $summary;
    }
}
