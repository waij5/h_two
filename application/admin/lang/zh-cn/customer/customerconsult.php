<?php

use app\admin\model\CocAcceptTool;
use app\admin\model\Osctype;

$res         = [];
$oscTypeList = Osctype::getList();
foreach ($oscTypeList as $key => $oscType) {
    $res['osc_type_' . $key] = $oscType;
}

$toolList = CocAcceptTool::getList();
foreach ($toolList as $key => $tool) {
    $res['accept_tool_' . $key] = $tool;
}

return array_merge($res, [
    'Cst_id'                                                  => 'ID',
    'admin_dept_id'                                           => '营销部门',
    'Arrive_status'                                           => '客户状态',
    'Customer_id'                                             => '顾客ID',
    'Ctm_id'                                                  => '客户卡号',
    'ctm_type'                                                => '客户类别',
    'Admin_id'                                                => '网络客服',
    'developStaff'                                            => '网络客服',
    'Admin_nickname'                                          => '受理人员',
    'consult_admin_name'                                      => '受理人员',
    'Ctm_name'                                                => '顾客',
    'Cst_projects'                                            => '客服项目',
    'Cst_docs'                                                => '客服提到的医生',
    'Cst_content'                                             => '备注',
    'Cst_status'                                              => '状态',
    'Fat_id'                                                  => '说明',
    'Cst_remark'                                              => '备注',
    'cst_updatetime'                                          => '受理时间',
    'PLz type phone.'                                         => '请输入客户号码',
    'Add customer consult'                                    => '新增顾客客服',
    'Basic info'                                              => '客户资料',
    'Extra info'                                              => '额外信息',
    'Ctm_depositamt'                                          => '定金',
    'Account info'                                            => '客户消费',
    'Customer info'                                           => '顾客信息',
    'ctm_rank_points'                                         => '等级积分',
    'ctm_pay_points'                                          => '消费积分',
    'Consult info'                                            => '客服信息',
    'Female'                                                  => '女性',
    'Male'                                                    => '男性',
    'Ctm_name'                                                => '客户姓名',
    'Ctm_sex'                                                 => '性别',
    'Ctm_birthdate'                                           => '出生日期',
    'Ctm_tel'                                                 => '联系电话',
    'Ctm_zip'                                                 => '邮编',
    'Ctm_addr'                                                => '地址',
    'Ctm_email'                                               => '邮箱',
    'Ctm_mobile'                                              => '手机号码',
    'Ctm_ifrevmail'                                           => '是否接收邮件',
    'Ctm_explore'                                             => '营销渠道',
    'Ctm_source'                                              => '客户来源',
    'Ctm_company'                                             => '客户公司',
    'Ctm_job'                                                 => '职业',
    'Ctm_psumamt'                                             => '总金额',
    'Ctm_salamt'                                              => '历史消费',
    'Ctm_discamt'                                             => '总折扣金额',
    'Ctm_ifbirth'                                             => '生日提醒',
    'Ctm_qq'                                                  => 'QQ',
    'Ctm_wxid'                                                => '微信',
    'Cpdt_id'                                                 => '客服项目',
    'ctm_first_search'                                        => '第一搜索词',
    'coc_Admin_nickname'                                      => '现场客服',
    'cst_Admin_nickname'                                      => '受理人员',
    'rec_customer_id'                                         => '推荐人',

    'NONE'                                                    => '--',
    'Ctm_remark'                                              => '备注',
    'Customer does not exist.'                                => '找不到顾客(ID: %s).',
    'Status_yes'                                              => '启用',
    'Status_no'                                               => '禁用',
    'Failed while trying to save customer data！'              => '保存顾客信息时失败！',
    'Failed while trying to save consult data！'               => '保存顾客客服信息时失败！',
    'Failed while trying to save book data！'                  => '保存顾客预约信息时失败！',
    'All data saved successfully.'                            => '所有数据成功保存',
    'Pdc_name'                                                => '客服项目',
    'Status_ng'                                               => '未预约',
    'Status_pending'                                          => '已预约',
    'Status_success'                                          => '已到诊',
    'Status_outdate'                                          => '已过期',
    'Book_time'                                               => '预约时间',

    'Records can not be delete!'                              => '无法删除记录！',
    'Pdc_id'                                                  => '客服项目',
    'Dept_id'                                                 => '客服科室',

    'Consult of this customer has existed(validate days: %s)' => '此顾客已有客服记录(有效天数：%s天)',
    'Customer %s(ID:%s) has arrived'                          => '顾客 %s(卡号:%s) 已上门',

    //consult tab
    'Consult history'                                         => '过往记录',
    'Osconsult history'                                       => '现场客服记录',
    'Osc_content'                                             => '客服内容',

    'PLz type customer id or phone!'                          => '请输入客户ID或者手机号码！',

    //osconsult
    'Osconsult history'                                       => '现场客服记录',
    'Osc_content'                                             => '客服内容',
    'Osc_id'                                                  => 'ID',
    'Osc_status'                                              => '客服状态',
    'Osc_type'                                                => '类型',
    'Pro_spec'                                                => '规格',
    'Type_id'                                                 => '受理类型',
    'Tool_id'                                                 => '受理工具',
    'ctm_first_tool_id'                                       => '首次受理工具',
    'Cpdt_name'                                               => '客服项目',
    'Operator'                                                => '指派人员',

    // 状态
    'Status_0'                                                => '已分派',
    'Status_1'                                                => '服务中',
    'Status_2'                                                => '成功',
    'Status_3'                                                => '已成交',
    'Status_m_1'                                              => '拒绝',
    'Status_m_2'                                              => '未成交',
    'Status_m_3'                                              => '中止',
    'arrive_yes'                                              => '已上门',
    'arrive_no'                                               => '未上门',

    'Updatetime'                                              => '更新时间',

    //rvinfo
    'Rvinfo history'                                          => '回访记录',
    'Rv_create_time'                                          => '登记时间',
    'Rv_admin_id'                                             => '回访人员',
    'Rv_date'                                                 => '回访日期',
    'Rv_time'                                                 => '回访时间',
    'Rv_plan'                                                 => '回访计划',
    'Rv_status'                                               => '状态',
    'Rv_fat_id'                                               => '流失原因',
    'Rvi_id'                                                  => 'ID',
    'Rvi_tel'                                                 => '电话',
    'Customer_id'                                             => '顾客',
    'Rvt_type'                                                => '回访类型',
    'Rvi_content'                                             => '回访情况',
    'Rv_resolve_result'                                       => '处理结果',
    'Rv_resolve_admin_id'                                     => '处理人',
    'rv_status_none'                                          => '--',
    'rv_status_0'                                             => '失败',
    'rv_status_1'                                             => '成功',

    //order
    'Order history'                                           => '订单情况',
    'Order basic info'                                        => '订单基本信息',
    'Order_id'                                                => 'ID',
    'Local_total'                                             => '本地金额',
    'Ori_total'                                               => '原金额',
    'Min_total'                                               => '最低金额',
    'Discount_amount'                                         => '折扣(元)',
    'Discount_percent'                                        => '折扣(%)',
    'Total'                                                   => '折后金额',
    'Undeducted_total'                                        => '未划扣金额',
    'Order_status'                                            => '订单状态',
    'Ctm_name'                                                => '顾客',
    'Admin_Id'                                                => '现场客服',
    'Createtime'                                              => '来电时间',
    'Updatetime'                                              => '更新时间',

    'Customer_id'                                             => '顾客',

    //order status
    'order_status_m_3'                                        => '审批中',
    'order_status_m_2'                                        => '退款',
    'order_status_m_1'                                        => '撤单',
    'order_status_0'                                          => '待付款',
    'order_status_1'                                          => '已付款',
    'order_status_2'                                          => '已完成',
    'cst_Createtime'                                          => '分诊时间',
    'coctime'                                                 => '分诊时间',
    'order_pay_total'                                         => '实付金额',
    'coc_admin_id'                                            => '现场客服',

    'A consult exists, are sure to add another by force?'     => '客服已存在，是否强制添加？',
    'clear'                                                   => '清除',
    'ctm_age'                                                 => '客户年龄',
    'THE MOBILE IS EXIST'                                     => '手机号码已存在',
    'THE TEL IS EXIST'                                        => '电话号码已存在',
    'customer customerconsult'                                => '网电客服管理',

]);
