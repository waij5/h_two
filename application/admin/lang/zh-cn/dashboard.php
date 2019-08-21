<?php

use app\admin\model\Msgtype;

$msgTypeList = Msgtype::getList();

$res = array();
foreach ($msgTypeList as $key => $msgtype) {
    $res['msgtype_' . $key] = $msgtype;
}
return array_merge($res, [
    'Msg_id'                           => 'ID',
    'Msg_type'                         => '消息类型',
    'Msg_from'                         => '发送人，默认系统0',
    'Msg_to'                           => '接收人ID',
    'Msg_title'                        => '消息标题',
    'Msg_content'                      => '消息内容',
    'Createtime'                       => '创建时间',
    'Updatetime'                       => '阅读时间',

    'Ctm_id'                           => '客户卡号',
    'Ctm_pass'                         => '密码',
    'Ctm_name'                         => '客户姓名',
    'ctm_type'                         => '客户类别',
    // 0隐私，1女，2男，默认0',
    'Ctm_sex'                          => '性别',
    'Ctm_birthdate'                    => '出生日期',
    'Ctm_tel'                          => '联系电话',
    'Ctm_zip'                          => '邮编',
    'Ctm_addr'                         => '地址',
    'Ctm_email'                        => '邮箱',
    'Ctm_mobile'                       => '手机号码',
    'Ctm_ifrevmail'                    => '是否接收邮件',
    'Ctm_explore'                      => '营销渠道',
    'Ctm_source'                       => '客户来源',
    'Ctm_company'                      => '客户公司',
    'Ctm_job'                          => '职业',
    'Ctm_remark'                       => '备注',
    'Ctm_depositamt'                   => '定金',
    'Ctm_psumamt'                      => '总金额',
    'Ctm_salamt'                       => '历史消费',
    'Ctm_discamt'                      => '总折扣金额',
    'Ctm_ifbirth'                      => '生日提醒',
    'Createtime'                       => '创建时间',
    'ctm_rank_points'                  => '等级积分',
    'ctm_pay_points'                   => '消费积分',
    'Updatetime'                       => '更新时间',
    'Admin_id'                         => '创建人',
    'Operator'                         => '指派人员',
    'Ctm_qq'                           => 'QQ',
    'Ctm_wxid'                         => '微信',
    'ctm_first_search'                 => '第一搜索词',
    'rec_customer_id'                  => '推荐人',
    'developStaff'                     => '网络客服',
    'dept_id'                          => '营销部门',

    'Reassign develop staff'           => '修改网络客服',
    'Invalid develop staff!'           => '无效的网络客服',

    'arrive_yes'                       => '已上门',
    'arrive_no'                        => '未上门',

    // 状态
    'Status_0'                         => '已分派',
    'Status_1'                         => '服务中',
    'Status_2'                         => '成功',
    'Status_3'                         => '已成交',
    'Status_m_1'                       => '拒绝',
    'Status_m_2'                       => '未成交',
    'Status_m_3'                       => '中止',

    'Status_yes'                       => '启用',
    'Status_no'                        => '禁用',

    'Female'                           => '女性',
    'Male'                             => '男性',

    'Status_yes'                       => '启用',
    'Status_no'                        => '禁用',

    'Female'                           => '女性',
    'Male'                             => '男性',

    'arrive_status'                    => '上门状态',

]);
