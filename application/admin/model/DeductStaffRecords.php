<?php

namespace app\admin\model;

use think\Model;

class DeductStaffRecords extends Model
{
    // 表名
    protected $name = 'deduct_staff_records';
    
    // 自动写入时间戳字段
    // protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    // protected $createTime = 'createtime';
    // protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];
    

     /**
     * 手术业绩
     */
    public static function operateBenefitSummary($where, $includeSummary = false)
    {
        if ($includeSummary) {
            $fields = 'count(*) as count, sum(deduct_times) as total_deduct_times, sum(deduct_amount) total_deduct_amount, sum(deduct_benefit_amount) as total_deduct_benefit_amount, sum(final_amount) as total_final_amount, sum(final_benefit_amount) as total_final_benefit_amount';
        } else {
            $fields = 'count(*) as count';
        }

        $summarys = self::alias('staff_rec')
                ->join(model('admin/DeductRecords')->getTable() . ' rec', 'staff_rec.deduct_record_id = rec.id', 'INNER')
                ->join(model('admin/OrderItems')->getTable() . ' items', 'rec.order_item_id = items.item_id', 'LEFT')
                ->join(model('admin/Admin')->getTable() . ' admin', 'staff_rec.admin_id = admin.id', 'LEFT')
                ->where($where)
                ->limit(1)
                ->column($fields);
        $summary = current($summarys);
        if (is_array($summary)) {
            foreach ($summary as $key => $value) {
                if ($value == null) {
                    $summary[$key] = 0.00;
                } else {
                    $summary[$key] = floor($value * 100) / 100;
                }
            }
        } else {
            $count = $summary;
            $summary = array();
            $summary['count'] = $count;
        }

        return $summary;
    }

    public static function operateBenefitList($where, $sort, $order, $offset, $limit)
    {
        $list = self::alias('staff_rec')
                ->join(model('admin/DeductRecords')->getTable() . ' rec', 'staff_rec.deduct_record_id = rec.id', 'INNER')
                ->join(model('admin/DeductRole')->getTable() . ' deduct_role', 'staff_rec.deduct_role_id = deduct_role.id', 'LEFT')
                ->join(model('admin/OrderItems')->getTable() . ' items', 'rec.order_item_id = items.item_id', 'LEFT')
                ->join(model('admin/Admin')->getTable() . ' admin', 'staff_rec.admin_id = admin.id', 'LEFT')
                ->join(model('admin/Customer')->getTable() . ' customer', 'items.customer_id = customer.ctm_id')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->field('staff_rec.*, rec.*, admin.username, admin.nickname, admin.position, items.item_type, items.pro_id, items.pro_name, items.pro_spec, items.item_cost, items.dept_id, items.item_pay_amount_per_time, items.item_pay_amount_per_time, items.item_pay_total, items.item_total, items.item_amount, items.item_coupon_total, items.customer_id, customer.ctm_name, deduct_role.name as deduct_role_name')
                ->select();

        return $list;
    }
}
