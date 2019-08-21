<?php

namespace app\admin\model;

use think\Model;

class OrderChangeLog extends Model
{
    // 表名
    protected $name = 'order_change_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];

    //换/退 项目
    const TYPE_SWITCH = 'CHANGE_TYPE_SWITCH';
    const TYPE_RETURN = 'CHANGE_TYPE_RETURN';

    public static function getListCount($where, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        $summary = static::alias('order_change_log')
                ->join(\app\admin\model\Customer::getTable() . ' customer', 'order_change_log.customer_id = customer.ctm_id', 'LEFT')
                ->join(\app\admin\model\Admin::getTable() . ' admin', 'order_change_log.admin_id = admin.id', 'LEFT')
                ->join(\app\admin\model\OrderItems::getTable() . ' order_items', 'order_change_log.original_item_id = order_items.item_id', 'LEFT')
                ->where($where)
                ->where($extraWhere)
                ->field('count(*) as count, sum(deposit_change) as deposit_change')
                ->find();

        return ['count' => intval($summary['count']), 'deposit_change' => floatval($summary['deposit_change'])];
    }

    public static function getList($where, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        $list = static::alias('order_change_log')
                ->join(\app\admin\model\Customer::getTable() . ' customer', 'order_change_log.customer_id = customer.ctm_id', 'LEFT')
                ->join(\app\admin\model\Admin::getTable() . ' admin', 'order_change_log.admin_id = admin.id', 'LEFT')
                ->join(\app\admin\model\OrderItems::getTable() . ' order_items', 'order_change_log.original_item_id = order_items.item_id', 'LEFT')
                ->where($where)
                ->where($extraWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->field('order_change_log.*, customer.ctm_name, order_items.admin_id as osconsult_admin_id, customer.admin_id as develop_admin_id, order_items.consult_admin_id, order_items.recept_admin_id')
                ->select();

        $briefAdminList = \app\admin\model\Admin::getAdminCache(\app\admin\model\Admin::ADMIN_BRIEF_CACHE_KEY);

        foreach ($list as $key => $row) {
            $list[$key]['osconsult_admin_name'] = ($row['osconsult_admin_id'] && isset($briefAdminList[$row['osconsult_admin_id']])) ? $briefAdminList[$row['osconsult_admin_id']] : '';
            $list[$key]['develop_admin_name'] = ($row['develop_admin_id'] && isset($briefAdminList[$row['develop_admin_id']])) ? $briefAdminList[$row['develop_admin_id']] : '';
            $list[$key]['consult_admin_name'] = ($row['consult_admin_id'] && isset($briefAdminList[$row['consult_admin_id']])) ? $briefAdminList[$row['consult_admin_id']] : '';
            $list[$key]['recept_admin_name'] = ($row['recept_admin_id'] && isset($briefAdminList[$row['recept_admin_id']])) ? $briefAdminList[$row['recept_admin_id']] : '';
            $list[$key]['operator'] = ($row['admin_id'] && isset($briefAdminList[$row['admin_id']])) ? $briefAdminList[$row['admin_id']] : '';
        }

        return $list;
    }
}
