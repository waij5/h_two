<?php
use app\admin\model\Chntype;

// $chntypeList = Chntype::getList();
$chnTypeLists = model('exprtype')->field('ept_id,ept_name')->select();

$res = array();
// foreach ($chntypeList as $key => $chntype) {
//     $res['chtype_' . $key] = $chntype;
// }
foreach ($chnTypeLists as $key => $value) {
    $res['ept_'.$value['ept_id']] = $value['ept_name'];
}

return array_merge($res, [
    'Chn_id'  =>  'ID',
    'Chn_code'  =>  '编码',
    'Chn_name'  =>  '名称',
    'Chn_type'  =>  '类型',
    'Chn_uid'  =>  '添加人',
    'Chn_status'  =>  '状态',
    'Chn_sort'  =>  '排序',
    'Chn_remark'  =>  '备注',
    'Createtime'  =>  '创建时间',
    'Updatetime'  =>  '更新时间'
]);
