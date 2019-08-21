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
    'batch adminid'                                 => '批量修改网络客服',
    'batch oscadminid'                              => '批量修改现场客服',
    'Customer createtime'                           => '录入时间',
    // 'Ctm_id'                           => '客户卡号',
    // 'Ctm_pass'                         => '密码',
    // 'Ctm_name'                         => '客户姓名',
    // 'ctm_type'                         => '客户类别',
    // 'ctm_age'                          => '客户年龄',
    // 'month'                            => '生日月份',

    // 0隐私，1女，2男，默认0',
    // 'Ctm_sex'                          => '性别',
    // 'Ctm_birthdate'                    => '出生日期',
    // 'Ctm_tel'                          => '联系电话',
    // 'Ctm_zip'                          => '邮编',
    // 'Ctm_addr'                         => '地址',
    // 'Ctm_email'                        => '邮箱',
    // 'Ctm_mobile'                       => '手机号码',
    // 'Ctm_ifrevmail'                    => '是否接收邮件',
    // 'Ctm_explore'                      => '营销渠道',
    // 'Ctm_source'                       => '客户来源',
    // 'Ctm_company'                      => '客户公司',
    // 'Ctm_job'                          => '职业',
    // 'Ctm_remark'                       => '备注',
    // 'Ctm_depositamt'                   => '定金',
    // 'Ctm_psumamt'                      => '总金额',
    // 'Ctm_salamt'                       => '历史消费',
    // 'Ctm_discamt'                      => '总折扣金额',
    // 'Ctm_ifbirth'                      => '生日提醒',
    // 'Createtime'                       => '创建时间',
    // 'ctm_rank_points' => '等级积分',
    // 'ctm_pay_points' => '消费积分',
    'Updatetime'                                    => '更新时间',
    'Admin_id'                                      => '创建人',
    'Operator'                                      => '指派人员',
    'Ctm_qq'                                        => 'QQ',
    'Ctm_wxid'                                      => '微信',
    'ctm_first_search'                              => '第一搜索词',
    'rec_customer_id'                               => '推荐人',
    'developStaff'                                  => '网络客服',
    'dept_id'                                       => '营销部门',

    'Reassign develop staff'                        => '修改网络客服',
    'Invalid develop staff!'                        => '无效的网络客服',

    'arrive_yes'                                    => '已上门',
    'arrive_no'                                     => '未上门',

    // 状态
    // 'Status_0'               => '已分派',
    // 'Status_1'               => '服务中',
    // 'Status_2'               => '成功',
    // 'Status_m_1'             => '拒绝',
    // 'Status_m_2'             => '失败',
    // 'Status_m_3'             => '中止',

    // 状态
    'Status_0'                                      => '已分派',
    'Status_1'                                      => '服务中',
    'Status_2'                                      => '成功',
    'Status_3'                                      => '已成交',
    'Status_m_1'                                    => '拒绝',
    'Status_m_2'                                    => '未成交',
    'Status_m_3'                                    => '中止',

    'Status_yes'                                    => '启用',
    'Status_no'                                     => '禁用',

    'Female'                                        => '女性',
    'Male'                                          => '男性',

    'Status_yes'                                    => '启用',
    'Status_no'                                     => '禁用',

    'Female'                                        => '女性',
    'Male'                                          => '男性',

    'Basic info'                                    => '基本信息',
    'Extra info'                                    => '额外信息',
    'Account info'                                  => '帐户信息',
    'Customer info'                                 => '顾客信息',
    'Assign osconsult'                              => '现场客服指派',

    'NONE'                                          => '  ',

    //consult
    'Consult history'                               => '过往记录',

    'Cst_id'                                        => 'ID',
    'Admin_nickname'                                => '网络客服',
    'Cst_status'                                    => '状态',
    'Fat_id'                                        => '失败原由',
    'Cst_content'                                   => '备注',
    'Pdc_name'                                      => '客服项目',
    'Status_ng'                                     => '未预约',
    'Status_pending'                                => '已预约',
    'Status_success'                                => '已到诊',
    'Status_outdate'                                => '已过期',
    'Book_time'                                     => '预约时间',
    'tool_id'                                       => '受理工具',
    'coc_Admin_nickname'                            => '现场客服',
    'cst_Admin_nickname'                            => '受理人员',

    //osconsult
    'Osconsult history'                             => '现场客服记录',
    'Osc_content'                                   => '客服内容',
    'Osc_id'                                        => 'ID',
    'Osc_status'                                    => '客服状态',
    'Osc_type'                                      => '类型',
    'Cpdt_name'                                     => '客服项目',

    //rvinfo
    'Rvinfo history'                                => '回访记录',
    'Rv_create_time'                                => '登记时间',
    'Rv_admin_id'                                   => '回访人员',
    'Rv_date'                                       => '回访日期',
    'Rv_time'                                       => '回访时间',
    'Rv_plan'                                       => '回访计划',
    'Rv_status'                                     => '状态',
    'Rv_fat_id'                                     => '流失原因',
    'Rvi_id'                                        => 'ID',
    'Rvi_tel'                                       => '电话',
    'Customer_id'                                   => '顾客',
    'Rvt_type'                                      => '回访类型',
    'Rvi_content'                                   => '回访情况',
    'Rv_resolve_result'                             => '处理结果',
    'Rv_resolve_admin_id'                           => '处理人',
    'rv_status_none'                                => '--',
    'rv_status_0'                                   => '失败',
    'rv_status_1'                                   => '成功',

    //order
    'Order history'                                 => '订单情况',
    'Order basic info'                              => '订单基本信息',
    'Order_id'                                      => 'ID',
    'Local_total'                                   => '本地金额',
    'Ori_total'                                     => '原金额',
    'Min_total'                                     => '最低金额',
    'Discount_amount'                               => '折扣(元)',
    'Discount_percent'                              => '折扣(%)',
    'Total'                                         => '折后金额',
    'Undeducted_total'                              => '未划扣金额',
    'Order_status'                                  => '订单状态',
    'Ctm_name'                                      => '顾客',
    'Admin_Id'                                      => '现场客服',
    'Createtime'                                    => '来电时间',
    'Updatetime'                                    => '更新时间',
    'Pro_spec'                                      => '规格',
    'Customer_id'                                   => '顾客',

    //order status
    'order_status_m_3'                              => '审批中',
    'order_status_m_2'                              => '退款',
    'order_status_m_1'                              => '撤单',
    'order_status_0'                                => '待付款',
    'order_status_1'                                => '已付款',
    'order_status_2'                                => '已完成',
    'arrive_status'                                 => '上门状态',

    // 'Order createtime'                 => '开单时间',
    // 'order_pay_total'                  => '实付金额',
    // 'rv_time'                          => '实际回访时间',

    'ctm_first_dept_id'                => '首次科室',
    'ctm_first_cpdt_id'                => '首次项目',

    'ctm_first_dept_id'                                            => '首次科室',
    'ctm_first_cpdt_id'                                            => '首次项目',

    'ctm_first_recept_time'            => '首次到诊时间',
    'ctm_last_recept_time'             => '最近到诊时间',
    'ctm_first_osc_admin'              => '首次现场客服',
    'ctm_last_osc_admin'               => '最近现场客服',
    'ctm_first_osc_cpdt_id'            => '首次项目',
    'ctm_last_osc_cpdt_id'             => '最近项目',
    'ctm_first_osc_dept_id'            => '首次科室',
    'ctm_last_osc_dept_id'             => '最近科室',
    // 'ctm_first_tool'                                => '首次受理工具',

    'Reassign customer osconsult data'              => '重新分配现场客服数据',
    'Reassign customer adminid data'                => '重新分配网络客服',
    'Selected customer count'                       => '选中顾客数',
    'Selected customers'                            => '选中顾客',

    'Customer profiles export'                      => '顾客资料导出',
    'batch addrvtype'                               => '批量增加回访计划',
    'cst_Rvd_days'                                  => '回访日期',
    'Rvdays\'s rvplan and days set must be unique!' => '回访计划设置回访计划和天数的组合必须唯一，即同一回访计划不能有两个相同的天数间隔设置!',
    'ctm_status'                                    => '废弃状态',
    'customermobile'                                => '修改手机号',
    
    'batch publicOut'                               => '批量移出公有池',
    'batch invalidOut'                              => '批量移出废弃池',
    'IS batchinvalidout?'                           => '确定批量移出废弃池?',
    'IS batchpublicout?'                            => '确定批量移出公有池?',
    'Is Discarded?'                                 => '确认废弃该客户?',
    'Is Out publiccustomer?'                        => '确认将该客户移出公有池?',
    'Is Out invalidcustomerOut?'                    => '确认将该客户移出废弃池?',
    'Operation false'                               => '申请失败,该客户已被标记为废弃客户',
    'ctm_status'                                    => '废弃状态',
    'MergeHisCustomer'                              => '合并客户',
    'Customer profiles merge'                       => '合并客户中',

]);
