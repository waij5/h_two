<?php
use app\admin\model\Msgtype;

$msgTypeList = Msgtype::getList();

$res = array();
foreach($msgTypeList as $key => $msgtype){
	$res['msgtype_' . $key] = $msgtype;
}
return array_merge($res, [
    'Msg_id'  =>  'ID',
    'Msg_type'  =>  '消息类型',
    'Msg_from'  =>  '发送人，默认系统0',
    'Msg_to'  =>  '接收人ID',
    'Msg_title'  =>  '消息标题',
    'Msg_content'  =>  '消息内容',
    'Createtime'  =>  '创建时间',
    'Updatetime'  =>  '阅读时间'
]);