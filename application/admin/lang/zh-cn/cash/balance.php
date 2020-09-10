<?php

return [

    'Balance_id'                                                                                                         => 'ID',
    'Customer_id'                                                                                                        => '顾客卡号',
    'Customer_name'                                                                                                      => '顾客',
    'Balance_type'                                                                                                       => '类型',
    'Total'                                                                                                              => '总金额',
    'Pay_total'                                                                                                          => '支付额',
    'Coupon_total'                                                                                                       => '优惠券额',
    'Deposit_total'                                                                                                      => '定金支付额',
    'Admin_id'                                                                                                           => '操作人',
    'Createtime'                                                                                                         => '收银时间',
    'Balance_remark'                                                                                                     => '备注',
    'Deptment_id'                                                                                                        => '科室',
    'Rec_admin_id'                                                                                                       => '推介人',
    'cst_admin_id'                                                                                                       => '网络客服',
    'Order id'                                                                                                           => '业务单',

    'Balance summary'                                                                                                    => '营收概况',

    'b_pay_total'                                                                                                        => '营收总额',
    'b_order_count'                                                                                                      => '业务单数',
    'b_in_pay_total'                                                                                                     => '收款总额',
    'b_out_pay_total'                                                                                                    => '退款总额',

    'b_in_Pay_total'                                                                                                     => '总收款',
    'b_in_cash_pay_total'                                                                                                => '现金收款',
    'b_in_card_pay_total'                                                                                                => '卡收款',
    'b_in_wechatpay_pay_total'                                                                                           => '微信收款',
    'b_in_alipay_pay_total'                                                                                              => '支付宝收款',
    'b_in_other_pay_total'                                                                                               => '其它收款',

    'Stat_total'                                                                                                         => '营收总额',
    'Today Stat total'                                                                                                   => '今日营收',
    'Stat_amount'                                                                                                        => '业务单数',
    'stat_business_total'                                                                                                => '业务总额',
    'Stat_cash_total'                                                                                                    => '现金收款',
    'Stat_card_total'                                                                                                    => '刷卡收款',
    'stat_extra_pay_total'                                                                                               => '其它方式收款',
    'stat_coupon_cost_total'                                                                                             => '消费券收款',
    'Stat_coupon_total'                                                                                                  => '消费券抵用额',
    'Stat_other_total'                                                                                                   => '其它收入',
    'Stat_deposit_total'                                                                                                 => '定金使用',
    'Stat_refund_total'                                                                                                  => '退款总额',
    'stat_adjust_income_total'                                                                                           => '冲减收入',
    'stat_adjust_outpay_total'                                                                                           => '冲减支出',
    'Stat_date'                                                                                                          => '营业日期',

    'Receipt Print'                                                                                                      => '收据',
    'Invoice Print'                                                                                                      => '发票',

    //customer
    'Ctm_id'                                                                                                             => '顾客ID',
    'Ctm_name'                                                                                                           => '顾客姓名',
    'Ctm_mobile'                                                                                                         => '顾客电话',

    'Prestore'                                                                                                           => '预存',
    'New order'                                                                                                          => '开单',
    'Prestore receipt'                                                                                                   => '定金单',
    'Prestore amount'                                                                                                    => '预存金额',
    'Prestored amount'                                                                                                   => '现有定金',

    'Refund'                                                                                                             => '退款',
    'Refund amount'                                                                                                      => '退款金额',
    'Refund receipt'                                                                                                     => '退款单',

    'Buy coupon'                                                                                                         => '购买优惠券',
    'Return coupon'                                                                                                      => '退优惠券',
    'Coupon receipt'                                                                                                     => '购券单',
    'Select coupon'                                                                                                      => '选择优惠券',
    'Use Pay points'                                                                                                     => '消费积分',
    'Use affiliate'                                                                                                      => '佣金',

    'Order receipt'                                                                                                      => '订单收款单',
    'Coupon'                                                                                                             => '优惠券',
    'Need pay total'                                                                                                     => '应支付额',
    'Pay total'                                                                                                          => '支付额',
    'Show/hide order'                                                                                                    => '显示/隐藏订单',

    'Balance adjust'                                                                                                     => '收支冲减',
    'Balance adjustment receipt'                                                                                         => '业务冲减',

    'Chargeback receipt'                                                                                                 => '项目退款单',
    'Original balance:'                                                                                                  => '原收银流水:',
    'Coupon pay amount'                                                                                                  => '购券费',
    'Coupon amount'                                                                                                      => '券面额',

    'Not enough coupon'                                                                                                  => '优惠券额不足',

    'Project list'                                                                                                       => '项目列表',
    'pro_name'                                                                                                           => '项目名',
    'item_qty'                                                                                                           => '数量',
    'pro_use_times'                                                                                                      => '单项次数',
    'item_used_times'                                                                                                    => '已划扣次数',
    'item_total_times'                                                                                                   => '总次数',
    'item_total'                                                                                                         => '金额',
    'chargebackAmt'                                                                                                      => '应退款',
    'This number maybe not equals to the above number, plz do not be puzzle.It is normarl, coupon calculation included.' => '此金额可能与上面的金额不对，不要疑惑，这是正常的，新的数值包含了优惠券方面的计算',

    'Not enough deposit'                                                                                                 => '定金余额不足',
    'Not enough pay_points'                                                                                              => '消费积分余额不足',
    'Not enough affiliate_amt'                                                                                           => '佣金余额不足',
    'Refund total can not be greater than ctm_depositamt!'                                                               => '退款金额不能大于客户实际定金金额',
    'Total can not be less than 0'                                                                                       => '金额不能小于0',
    'Failed when save coupon data, all operation has been undone.'                                                       => '保存优惠券失败，所有操作已撤销',
    'Failed when change depositamt, all operation has been undone.'                                                      => '修改客户定金失败，所有操作已撤销',
    'Failed when save order, all operation has been undone.'                                                             => '保存订单信息时失败，所有操作已撤销',
    'Failed when auto deduct, all operation has been undone.'                                                            => '自动划扣时失败，所有操作已撤销',

    'Order does not exists'                                                                                              => '订单不存在',
    'Can not pay order, stauts not matched!'                                                                             => '无法支付订单，订单状态不匹配',
    'Coupon does not exists!'                                                                                            => '优惠券不存在',
    'Invalid amount'                                                                                                     => '无效/异常金额',
    'Coupon "%s" can only be bought  %s times at most for every customer!'                                               => '优惠券 “%s” 每个顾客最多购买 %s 次',

    'Invalid coupon(not exists, not belong to this customer, used, expirated)'                                           => '优惠券无效(不存在，不属于此顾客，已使用，已过期)',

    //chargeback
    'TProType'                                                                                                           => '类型',
    'Pro_code'                                                                                                           => '项目编号',
    'TProName'                                                                                                           => '名称',
    'TProAmoutPerTime'                                                                                                   => '单价(次)',
    'Item_total_times'                                                                                                   => '总次数',
    'Item_qty'                                                                                                           => '数量',
    'Row_total'                                                                                                          => '总价',
    'Item_total'                                                                                                         => '折后总价',
    'Discount_percent'                                                                                                   => '折扣率(%)',
    'Deduct info'                                                                                                        => '划扣信息',
    'Cancel Coupon info'                                                                                                 => '扣券(面额)',
    'Chargeback total'                                                                                                   => '退回金额',
    'Type_project'                                                                                                       => '项目',
    'Type_product_1'                                                                                                     => '药品',
    'Type_product_2'                                                                                                     => '物品',
    'Failed to update customer deposit!'                                                                                 => '',

    'Failed when updating customer deposit info, all operation have been rolled back!'                                   => '更新用户定金信息时失败，所有操作已回滚！',
    'Balance detail'                                                                                                     => '收银明细',
    'yjy_developer_name'                                                                                                 => '定金网络客服',
    'osc_Admin_nickname'                                                                                                 => '定金现场客服',
    'nickname'                                                                                                           => '网络客服',
    'admin_dept_id'                                                                                                      => '营销部门',
    'returnCoupon receipt'                                                                                               => '退券单',
    'returnCoupon'                                                                                                       => '退优惠券',
    'refund_type'                                                                                                        => '退款类型',
    'customer_ctm_first_tool_id'                                                                                         => '首次受理工具',

    'Customer'                                                                                                           => '顾客',
    'Department'                                                                                                         => '科室',
    'Osconsult Department'                                                                                               => '客服科室',
    'cpdt_id'                                                                                                            => '客服项目',

    'Develop_admin'                                                                                                      => '网络客服',
    'Osconsult_admin'                                                                                                    => '现场客服',
    'First visit count'                                                                                                  => '初次',
    'Return visit count'                                                                                                 => '复次',
    'Reconsume count'                                                                                                    => '再消费',
    'Review count'                                                                                                       => '复查',
    'other count'                                                                                                        => '其他',
    'Reception total'                                                                                                    => '总接诊',
    'Reception total rate'                                                                                               => '总接诊率',

    'First visit total'                                                                                                  => '初次金额',
    'Return visit total'                                                                                                 => '复次金额',
    'Reconsume visit total'                                                                                              => '再消费金额',
    'Review total'                                                                                                       => '复查金额',
    'Other total'                                                                                                        => '其它金额',

    'Consumption total'                                                                                                  => '消费额',
    'Consumption per person'                                                                                             => '人均消费额',

    'Success count'                                                                                                      => '成功',
    'Success rate'                                                                                                       => '成功率',
    'Success total'                                                                                                      => '总成功',
    'Success total rate'                                                                                                 => '总成功率',
    'Percent'                                                                                                            => '占比%',

    'All business osconsult statistic'                                                                                   => '全业务现场客服统计',
    'Print date'                                                                                                         => '打印时期',
    'Summary duration from %s to %s'                                                                                     => '统计期间从%s到%s',
    'Osconsult date'                                                                                                     => '客服日期',
    'Recept date'                                                                                                        => '分诊时间',
    'Summary'                                                                                                            => '统计',
    'Osc_type'                                                                                                           => '类型',
];