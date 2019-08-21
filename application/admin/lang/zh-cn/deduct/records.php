<?php

use app\admin\model\Osctype;

$oscTypeList = Osctype::getList();
foreach ($oscTypeList as $key => $oscType) {
    $res['osc_type_' . $key] = $oscType;
}

return array_merge($res, [
    'Deduct records'                                                                          => '划扣记录',
    'Id'                                                                                      => 'ID',
    'Order_item_id'                                                                           => '项目/产品',
    'Deduct_times'                                                                            => '划扣次数',
    'Status'                                                                                  => '状态',
    'Admin_id'                                                                                => '划扣人',
    'Createtime'                                                                              => '创建时间',
    'Updatetime'                                                                              => '更新时间',

    'percent'                                                                                 => '提成比率(%)',
    'final_percent'                                                                           => '最终比率(%)',
    'final_amount'                                                                            => '提成金额(毛)',
    'final_benefit_amount'                                                                    => '提成金额(净)',

    'Deduct amount'                                                                           => '划扣金额(终)',
    'Deduct benefit amount'                                                                   => '本次收益(终)',
    'dealed by rate'                                                                          => '已乘以订单 实付/应付 系数',
    'Rate'                                                                                    => '系数',
    'Staff name'                                                                              => '职员名',
    'Staff benefit detail'                                                                    => '职员提成明细',

    'batch reverse deduct'                                                                    => '批量反划扣',
    'Order payed total divide order total'                                                    => '订单实付金额与订单折后金额的比值',

    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_PENGING                         => '未出库',
    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_COMPLETED                       => '已完成',
    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_REVERSE                         => '反划扣',
    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_REVERSED                        => '被反划扣',

    'Delivery'                                                                                => '出库',
    'Are sure to reverse deductions!'                                                         => '确认反划扣吗？',

    //反划扣
    'This deduct record could not be reversed: plz reverse delivering first!'                 => '此划扣记录无法被反划扣,请先撤销出库！',
    'This deduct record could not be reversed: incorrect status!'                             => '此划扣记录无法被反划扣,错误的划扣状态',
    'Failed to reverse deduction: could not find this order!'                                 => '反划扣失败：找不到对应的订单！',
    'Failed to reverse deduction: could not find this order item!'                            => '反划扣失败：找不到对应的订单项目！',
    'Failed to reverse deduction: error occurs while saving reverse deduction!'               => '反划扣失败：保存反划扣记录时失败！',
    'Failed to reverse deduction: error occurs while saving old deduction!'                   => '反划扣失败：更新原划扣记录时失败！',
    'Failed to reverse deduction: error occurs while updating order!'                         => '反划扣失败：更新订单项划扣信息失败！',
    'Failed to reverse deduction: error occurs while updating order!'                         => '反划扣失败：更新订单时失败！',

    'Admin_nickname'                                                                          => '划扣人',
    'deduct_amount'                                                                           => '划扣金额',
    'deduct_benefit_amount'                                                                   => '本次收益',
    'Status_0'                                                                                => '未出库',
    'Status_1'                                                                                => '已完成',
    'Status_2'                                                                                => '被反划扣',
    'Status_3'                                                                                => '反划扣',
    'dept_id'                                                                                 => '结算科室',
    'Deduct dept'                                                                             => '结算科室',

    'deduct_total'                                                                            => '划扣总金额',
    'deduct_benefit_total'                                                                    => '划扣总收益',
    'Customer'                                                                                => '顾客',
    'Deduct time'                                                                             => '划扣时间',
    'ctm_id'                                                                                  => '顾客卡号',
    'ctm_name'                                                                                => '顾客姓名',
    'item_spec'                                                                               => '规格',

    'Develop staff'                                                                           => '网络客服',
    'Osconsult staff'                                                                         => '现场客服',
    'Recept staff'                                                                            => '分派人员',
    'pro_name'                                                                                => '项目/产品',
    'pro_spec'                                                                                => '规格',
    'admin_dept_id'                                                                           => '营销部门',
    'coc_dept_id'                                                                             => '现场部门',
    'Ctm_explore'                                                                             => '营销渠道',
    'Ctm_source'                                                                              => '客户来源',
    'develop_admin_name'                                                                      => '网络客服',
    'ctm_first_tool'                                                                          => '首次受理工具',
    'Osc_type'                                                                                => '类型',
    'Pro_cat1'                                                                                => '类别一',
    'Pro_cat2'                                                                                => '类别二',
]);
