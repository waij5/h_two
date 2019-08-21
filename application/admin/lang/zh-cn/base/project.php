<?php

//use app\admin\model\Chntype;
//
////$chntypeList = Chntype::getList();
////
////$res = array();
////foreach ($chntypeList as $key => $chntype) {
////    $res['chtype_' . $key] = $chntype;
////}
$deptList = model('Deptment')->field("dept_id,dept_name")->select();

$res = array();
foreach ($deptList as $key => $val) {
    $res["dept_name_" . $val['dept_id']] = $val['dept_name'];
}

$feeType = \app\admin\model\Project::getList();
foreach ($feeType as $key => $val) {
    $res["fee_" . $key] = $val;
}
return array_merge($res, [
    'Pro_id'                         => 'ID',
    'Pro_code'                       => '项目编号',
    'Pro_name'                       => '项目名称',
    'Pro_spell'                      => '拼音码',
    'Pro_print'                      => '打印简称',
    'Subject_type'                   => '所属科目',
    'Customize Status'               => '定制化',
    'Pro_cat1'                       => '类别一',
    'Pro_cat2'                       => '类别二',
    'Pro_cat3'                       => '类别三',
    'Pro_unit'                       => '单位',
    'Pro_spec'                       => '规格',
    'Pro_use_times'                  => '使用次数',
    'Pro_amount'                     => '项目售价',
    'Pro_price'                      => '单次售价',
    'Pro_local_amount'               => '本地售价',
    'Pro_local_price'                => '本地单价',
    'Pro_min_price'                  => '最低售价',
    'Pro_cost'                       => '项目成本',
    'Deduct_addr'                    => '划扣地点',
    'Dept_id'                        => '结算科室',
    'Pro_fee_type'                   => '费用类型',
    'Pro_deadline'                   => '项目限期',
    'Allow_position_bonus'           => '按职位提成',
    'Allow_position_bonus 0'         => '否',
    'Allow_position_bonus 1'         => '是',
    'Allow_consult_calc'             => '客服成功率',
    'Deduct_switch'                  => '自动划扣',
    'Allow_bonus'                    => '赠送积分',
    'Pro_status'                     => '状态',
    'Pro_sort'                       => '排序',
    'Pro_remark'                     => '项目说明',
    'Createtime'                     => '创建时间',
    'Updatetime'                     => '更新时间',

    'All business project statistic' => '治疗项目管理',
]);
