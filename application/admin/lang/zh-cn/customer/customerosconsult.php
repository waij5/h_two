<?php

use app\admin\model\Osctype;
use app\admin\model\CocAcceptTool;

$res = array(
    // 状态
    'Status_0'   => '已分派',
    'Status_1'   => '服务中',
    'Status_2'   => '成功',
    'Status_3'   => '已成交',
    'Status_m_1' => '拒绝',
    'Status_m_2' => '未成交',
    'Status_m_3' => '中止',

    'Status_yes' => '启用',
    'Status_no'  => '禁用',

    'Female'     => '女性',
    'Male'       => '男性',
);

$oscTypeList = Osctype::getList();
foreach ($oscTypeList as $key => $oscType) {
    $res['osc_type_' . $key] = $oscType;
}

$toolList = CocAcceptTool::getList();
foreach ($toolList as $key => $tool) {
    $res['accept_tool_' . $key] = $tool;
}

return array_merge($res, [
    'Osc_id'                                                                       => 'ID',
    'Ctm_name'                                                                     => '客户姓名',
    'ctm_type'                                                                     => '客户类别',
    'Consult_id'                                                                   => '网电客服ID',
    'Consult_admin'                                                                => '网络客服',
    'Develop_admin'                                                                => '网络客服',
    'cst_admin_id'                                                                 => '受理人员',
    // 'Admin_id'                                                                     => '现场客服',
    'Operator'                                                                     => '指派人员',
    'Osc_content'                                                                  => '客服内容',
    // 'Osc_status'  =>  '客服状态0待接受，1服务中，2成功，-1拒绝--可重新指派，-2失败',
    'Osc_status'                                                                   => '客服状态',
    // 'Osc_type'  =>  '类型-初诊，复诊等',
    'Osc_type'                                                                     => '类型',
    'cst_Createtime'                                                               => '分诊时间',
    'Updatetime'                                                                   => '更新时间',
    'Cpdt_name'                                                                    => '客服项目',
    'Cpdt_id'                                                                      => '客服项目',
    'coc_Dept_id'                                                                  => '客服科室',
    'Chntype'                                                                      => '受理类型',
    'ctm_first_tool_id'                                                            => '受理工具',
    'ctm_last_recept_time'  =>  '最近来院时间',
    'ctm_rank_points' => '等级积分',
    'ctm_pay_points' => '消费积分',
    'tool_id'              => '受理工具',

    //operate
    'Accept'                                                                       => '接受指派',
    'Deny'                                                                         => '忙碌中，拒绝',

    'Osconsult %s does not exist'                                                  => '没有此客服(%s)',
    'Error occors!'                                                                => '发生了点错误',
    'Accept successfully.'                                                         => '成功接受了客服',

    'Are you sure you want to deny this consult?'                                  => '确认拒绝此项客服吗?',
    'You have accepted this consult, do you want to open the edit window?'         => '您已接受此客服，是否跳转编辑?',
    'Sorry,you can not modify a closed consult!'                                   => '很抱歉，您无法修改已经结束的客服',

    'total person-time'                                                            => '总人次',

    //add.html
    'Basic info'                                                                   => '基本信息',
    'Extra info'                                                                   => '额外信息',
    'Account info'                                                                 => '帐户信息',
    'Customer info'                                                                => '顾客信息',
    'Osconsult'                                                                    => '现场客服',
    'Osconsult info'                                                               => '现场客服指派内容',

    'Ctm_id'                                                                       => '客户卡号',
    'Ctm_name'                                                                     => '客户姓名',
    'Ctm_sex'                                                                      => '性别',
    'Ctm_birthdate'                                                                => '出生日期',
    'Ctm_mobile'                                                                   => '手机号码',
    'Ctm_tel'                                                                      => '联系电话',
    'Ctm_zip'                                                                      => '邮编',
    'Ctm_addr'                                                                     => '地址',
    'Ctm_email'                                                                    => '邮箱',
    'Ctm_mobile'                                                                   => '手机号码',
    'Ctm_ifrevmail'                                                                => '是否接收邮件',
    'Ctm_explore'                                                                  => '营销渠道',
    'Ctm_source'                                                                   => '客户来源',
    'Ctm_company'                                                                  => '客户公司',
    'Ctm_job'                                                                      => '职业',
    'Ctm_depositamt'                                                               => '定金',
    'Ctm_psumamt'                                                                  => '总金额',
    'Ctm_salamt'                                                                   => '总消费金额',
    'Ctm_discamt'                                                                  => '总折扣金额',
    'Ctm_ifbirth'                                                                  => '生日提醒',
    'Ctm_qq'                                                                       => 'QQ',
    'Ctm_wxid'                                                                     => '微信',
    'Ctm_remark'                                                                   => '备注',
    'ctm_first_search'                                                             => '第一搜索词',
    'rec_customer_id'                                                              => '推荐人',

    'NONE'                                                                         => '  ',
    'All'                                                                          => '所有',

    //consult tab
    'Consult history'                                                              => '过往客服',
    'Osconsult history'                                                            => '现场客服记录',

    'Cst_id'                                                                       => 'ID',
    'Admin_nickname'                                                               => '网络客服',
    'admin_dept_id'                                                               => '营销部门',
    'Cst_status'                                                                   => '状态',
    'Cst_content'                                                                  => '备注',
    'Fat_id'                                                                       => '失败原由',
    'Pdc_name'                                                                     => '客服项目',
    'Status_ng'                                                                    => '未预约',
    'Status_pending'                                                               => '已预约',
    'Status_success'                                                               => '已到诊',
    'Status_outdate'                                                               => '已过期',
    'Book_time'                                                                    => '预约时间',
    'Order has been created, you can check it by switching to tab "Order history"' => '已开单，您可以通过切换至“订单历史”标签页以查阅。',

    //rvinfo
    'Rvinfo history'                                                               => '回访记录',
    'Rv_create_time'                                                               => '登记时间',
   'coc_Admin_nickname' => '现场客服',
    'cst_Admin_nickname' => '受理人员',
    'Rv_admin_id'                                                                  => '回访人员',
    'Rv_date'                                                                      => '回访日期',
    'Rv_time'                                                                      => '回访时间',
    'Rv_plan'                                                                      => '回访计划',
    'Rv_status'                                                                    => '状态',
    'Rv_fat_id'                                                                    => '流失原因',
    'Rvi_id'                                                                       => 'ID',
    'Rvi_tel'                                                                      => '电话',
    'Customer_id'                                                                  => '顾客',
    'Rvt_type'                                                                     => '回访类型',
    'Rvi_content'                                                                  => '回访情况',
    'Rv_resolve_result'                                                            => '处理结果',
    'Rv_resolve_admin_id'                                                          => '处理人',
    'rv_status_none'                                                               => '--',
    'rv_status_0'                                                                  => '失败',
    'rv_status_1'                                                                  => '成功',

    //order
    'Order history'                                                                => '订单情况',
    'Order basic info'                                                             => '订单基本信息',
    'Order_id'                                                                     => 'ID',
    'Local_total'                                                                  => '本地金额',
    'Ori_total'                                                                    => '原金额',
    'Min_total'                                                                    => '最低金额',
    'Discount_amount'                                                              => '折扣(元)',
    'Discount_percent'                                                             => '折扣(%)',
    'Total'                                                                        => '折后金额',
    'Undeducted_total'                                                             => '未划扣金额',
    'Order_status'                                                                 => '订单状态',
    'Ctm_name'                                                                     => '顾客',
    'Admin_Id'                                                                     => '现场客服',
    'Createtime'                                                                   => '创建时间',
    'Updatetime'                                                                   => '更新时间',

    'Customer_id'                                                                  => '顾客',

    //order status
    'order_status_m_3'                                                             => '审批中',
    'order_status_m_2'                                                             => '退款',
    'order_status_m_1'                                                             => '撤单',
    'order_status_0'                                                               => '待付款',
    'order_status_1'                                                               => '已付款',
    'order_status_2'                                                               => '已完成',

    'Order'                                                                        => '订单',
    'New order'                                                                    => '开单',
    'Customer'                                                                     => '顾客',
    'Project list'                                                                 => '项目列表',
    'Add project'                                                                  => '增加项目',
    'Clear'                                                                        => '清空',

    //增加项目
    'Select project'                                                               => '选择项目',
    'Pro_id'                                                                       => 'ID',
    'Pro_code'                                                                     => '项目编号',
    'Pro_name'                                                                     => '项目名',
    'Pro_spell'                                                                    => '拼音码',
    'Pro_print'                                                                    => '打印简称',
    'Subject_type'                                                                 => '所属科目',
    'Pro_cat1'                                                                     => '所属类别',
    'Pro_cat2'                                                                     => '类别二',
    'Pro_unit'                                                                     => '单位',
    'Pro_spec'                                                                     => '规格',
    'Pro_use_times'                                                                => '使用次数',
    'Pro_amount'                                                                   => '项目价格',
    'Pro_price'                                                                    => '单次售价',
    'Pro_local_amount'                                                             => '本地售价',
    'Pro_local_price'                                                              => '本地单价',
    'Pro_min_price'                                                                => '最低售价',
    'Pro_cost'                                                                     => '项目成本',
    'Deduct_addr'                                                                  => '划扣地点',
    'Dept_id'                                                                      => '结算科室',

    'Project total'                                                                => '项目总计',

    'Item_qty'                                                                     => '数量',
    'Row_total'                                                                    => '总价',
    'Item_total'                                                                   => '折后总价',
    'Discount_percent'                                                             => '折扣率(%)',

    // order apply
    'Accept apply'                                                                 => '通过',
    'Deny apply'                                                                   => '拒绝',

    'Apply record'                                                                 => '审批记录',
    'Apply_info'                                                                   => '申请信息',
    'Reply_info'                                                                   => '回复信息',
    'Reply_status'                                                                 => '审批结果',
    'Apply_admin_id'                                                               => '申请人',
    'Reply_admin_id'                                                               => '回复人',

    'Pay order'                                                                    => '订单收款',
    'order_pay_total'                                                                                       => '实付金额',
    'Chargeback'                                                                   => '退款',

    'Rvd_id'                                                                       => 'ID',
    'Rvplan_id'                                                                    => '回访计划',
    'Rvd_days'                                                                     => '间隔天数',
    'cst_Rvd_days'                                                                 => '回访日期',
    'Rvd_status'                                                                   => '状态',
    'Rvd_remark'                                                                   => '备注',

    'Rvdays\'s rvplan and days set must be unique!'                                => '回访计划设置回访计划和天数的组合必须唯一，即同一回访计划不能有两个相同的天数间隔设置!',

      'cst_tool_id' => '受理工具',
    'customer_ctm_first_tool_id' => '首次受理工具',
    'Admin_id_Admin_id' => '现场客服',
    'customer customerosconsult' => '客户到诊信息',
    'ctm_age'                => '客户年龄',
    'ctm_createtime'  =>  '录入系统时间',
]);
