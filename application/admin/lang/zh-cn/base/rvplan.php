<?php
use app\admin\model\Rvtype;

$res         = [];
$rvtypeList = Rvtype::column('rvt_name','rvt_id');
foreach ($rvtypeList as $key => $List) {
    $res['rvtype_' . $key] = $List;
}

return array_merge($res, [
    'Rvp_id'  =>  'ID',
    'Rv_type'  =>  ' 回访类型',
    'Rvp_name'  =>  '回访计划名',
    'Rvp_status'  =>  '状态',
    'Rvp_remark'  =>  '备注',
    'Is_deletable'  =>  '是否可删除',

    'Rvd_id'  =>  'ID',
    'Rvplan_id'  =>  '回访计划',
    'Rvd_days'  =>  '间隔天数',
    'Rvd_status'  =>  '状态',
    'Rvd_remark'  =>  '备注',

    'rvtype_id'  =>  '回访类型',
    'rvd_name'    =>  '回访名',


    'Can\'t delete resource when it has been set undeletable!'  =>  '无法删除被设置为不可删除的资源！',
    'Rvdays\'s rvplan and days set must be unique!'  =>  '回访计划设置回访计划和天数的组合必须唯一，即同一回访计划不能有两个相同的天数间隔设置！',
]);
