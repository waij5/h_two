<?php

use app\admin\model\CocAcceptTool;
use app\admin\model\Osctype;

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
    'All'                                                   => '所有',
    'accept_tool_0'                                         => '',
    'Osc_id'                                                => 'ID',
    'Consult_id'                                            => '网电客服ID',
    'coc_Admin_nickname'                                    => '现场客服',
    'cst_Admin_nickname'                                    => '受理人员',
    'Coc.dept_id'                                           => '现场科室',
    'Admin_id'                                              => '现场客服',
    'Operator'                                              => '指派人员',
    'ctm_type'                                              => '客户类别',
    'Osc_content'                                           => '客服内容',
    // 'Osc_status'  =>  '客服状态0待接受，1服务中，2成功，-1拒绝--可重新指派，-2失败, -3未客服结束',
    'Osc_status'                                            => '现场服务状态',
    // 'Osc_type'  =>  '类型-初诊，复诊等',
    'Osc_type'                                              => '类型',
    'Createtime'                                            => '分诊时间',
    'Updatetime'                                            => '更新时间',
    'Consult_admin'                                         => '网络客服',
    'Develop_admin'                                         => '网络客服',
    'ctm_first_search'                                      => '第一搜索词',
    'ctm_rank_points'                                       => '等级积分',
    'ctm_pay_points'                                        => '消费积分',
    'rec_customer_id'                                       => '推荐人',
    'tool_id'                                               => '受理工具',
    'ctm_first_tool_id'                                     => '首次受理工具',
    'customer_ctm_first_tool_id'                            => '首次受理工具',
    'admin_dept_id'                                         => '营销部门',

    //对原有  批量 编辑/删除 的修改
    'Edit'                                                  => '改派',
    'delete'                                                => '完工',
    'Are you sure you want to delete the %s selected item?' => '确定结束此次客服吗？',
    'Are you sure you want to close this consult?'          => '确定结束此次客服吗？',
    'Access denied!'                                        => '拒绝访问',
    'Completed! %s items changed.'                          => '完成！共%s项修改。',
    'Admin_id can not be empty'                             => '请指定客服人员',
    'Osconsult content can not be empty'                    => '请输入客服内容',
    'Add customer osconsult'                                => '新增现场客服',
    'Ctm_book'                                              => '预约',

    'AddCustomer'                                           => '新增顾客',
    'Invalid phone number!'                                 => '无效的通讯号码',
    'Osconsult %s can not be reassigned!'                   => '现场客服(ID:%s)无法被改派',

    //add.html
    'Basic info'                                            => '基本信息',
    'Extra info'                                            => '额外信息',
    'Account info'                                          => '帐户信息',
    'Customer info'                                         => '顾客信息',
    'Assign osconsult'                                      => '现场客服指派',

    'Ctm_id'                                                => '客户卡号',
    'Ctm_name'                                              => '客户姓名',
    'Ctm_sex'                                               => '性别',
    'Ctm_birthdate'                                         => '出生日期',
    'Ctm_mobile'                                            => '手机号码',
    'Ctm_tel'                                               => '联系电话',
    'Ctm_zip'                                               => '邮编',
    'Ctm_addr'                                              => '地址',
    'Ctm_email'                                             => '邮箱',
    'Ctm_mobile'                                            => '手机号码',
    'Ctm_ifrevmail'                                         => '是否接收邮件',
    'Ctm_explore'                                           => '营销渠道',
    'Ctm_source'                                            => '客户来源',
    'Ctm_company'                                           => '客户公司',
    'Ctm_job'                                               => '职业',
    'Ctm_psumamt'                                           => '总金额',
    'Ctm_depositamt'                                        => '定金',
    'Ctm_salamt'                                            => '实际总金额',
    'Ctm_discamt'                                           => '总折扣金额',
    'Ctm_ifbirth'                                           => '生日提醒',
    'Ctm_qq'                                                => 'QQ',
    'Ctm_wxid'                                              => '微信',
    'Ctm_remark'                                            => '备注',

    'Ctm_id/book number'                                    => '顾客ID/预约号',

    'NONE'                                                  => '  ',
    'Cst_status'                                            => '状态',
    'Fat_id'                                                => '失败原由',
    'Cst_remark'                                            => '备注',
    'Cst_content'                                           => '客服内容',
    'Cpdt_id'                                               => '客服项目',
    'Pdc_id'                                                => '客服项目',
    'Dept_id'                                               => '客服科室',
    'Cpdt_name'                                             => '客服项目',

    'Cst_content'                                           => '网电客服',

    'Failed while trying to save osconsult data！'           => '指派现场客服时失败',

    'Records can not be delete!'                            => '无法删除记录！',

    //consult tab
    'Consult history'                                       => '过往记录',
    'Osconsult history'                                     => '受理情况',
    'Order history'                                         => '订单情况',

    'Cst_id'                                                => 'ID',
    'Admin_nickname'                                        => '网络客服',
    'Cst_status'                                            => '状态',
    'Cst_content'                                           => '备注',
    'Fat_id'                                                => '失败原由',
    'Pdc_name'                                              => '客服项目',
    'Status_ng'                                             => '未预约',
    'Status_pending'                                        => '已预约',
    'Status_success'                                        => '已到诊',
    'Status_outdate'                                        => '已过期',
    'order_pay_total'                                       => '实付金额',
    'Book_time'                                             => '预约时间',
    'Pro_spec'                                              => '规格',
    'Order_status'                                          => '订单状态',
    'order_status_m_3'                                      => '审批中',
    'order_status_m_2'                                      => '退款',
    'order_status_m_1'                                      => '撤单',
    'order_status_0'                                        => '待付款',
    'order_status_1'                                        => '已付款',
    'order_status_2'                                        => '已完成',

    'PLz type customer id or phone!'                        => '请输入客户ID或者手机号码！',
    'Customer recept'                                       => '顾客分诊',
    'One osconsult exists for this customer of today'       => '此顾客今日已有分诊',
    'admin_dept_name'                                       => '营销部门',
    'total person-time'                                     => '总人次',
    'THE MOBILE IS EXIST'                                   => '手机号码已存在',
    'THE TEL IS EXIST'                                      => '电话号码已存在',
]);
