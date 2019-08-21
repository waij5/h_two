<?php

return [
    'Order_num'  =>  '领料单号',
    'Producer_id'  =>  '供应商',
    'Depot_id'  =>  '所属仓库',
    'Depart_id'  =>  '领料科室',
    'Out_id'  =>  '领料人',
    'Member_id'  =>  '所属客户',
    'Uid'  =>  '操作人',
    'Type'  =>  '操作类型',
    'Type 0'  =>  '领药',
    'Type 1'  =>  '领料',
    'Status'  =>  '状态',
    'Status normal'  =>  '显示',
    'Status hidden'  =>  '隐藏',
    'Remark'  =>  '备注',
    'Createtime'  =>  '领药时间',
    'Updatetime'  =>  '修改时间',
    'Add'  =>  '领药',
    'SelDrugs'  =>  '选择药品',

    'Med_num'  =>  '药品编号',
    'Med_name'  =>  '药品名称',
    'Med_code' =>  '拼音码',
    'Med_stock'  =>  '可用库存',
    'Med_unit'  =>  '单位',
    'Med_price'  =>  '药品售价',
    'Med_cost'  =>  '药品成本价',
    'Med_remark'  =>  '备注',
    'Clear'      =>   '清空',


    'Order_id'  =>  'ID',
    'Order_type' => '订单类型',
    'Local_total'  =>  '本地金额',
    'Ori_total'  =>  '原金额',
    'Min_total'  =>  '最低金额',
    'Discount_amount'  =>  '折扣(元)',
    'Discount_percent'  =>  '折扣(%)',
    'Total'  =>  '折后金额',
    'Undeducted_total'  =>  '未划扣金额',
    'Order_status'  =>  '订单状态',
    'Ctm_name'  =>  '顾客',
    'Admin_Id'  =>  '操作人',
    'Createtime'  =>  '创建时间',
    'Updatetime'  =>  '更新时间',
    'Customer_id'  =>  '顾客',


    //order status
    'order_status_m_3'  =>  '审批中',
    'order_status_m_2'  =>  '退款',
    'order_status_m_1'  =>  '撤单',
    'order_status_0'  =>  '待付款',
    'order_status_1'  =>  '已付款',
    'order_status_2'  =>  '已完成',

    'Order_type_project'  =>  '项目单',
    'Order_type_product_1'  =>  '处方单',
    'Order_type_product_2'  =>  '产品单',




    'Order basic info'  =>  '订单基本信息',
    'TProType'  =>  '类型',
    'Pro_code'  =>  '项目编号',
    'TProName'  =>  '名称',
    'Pro_unit'  =>  '单位',
    'Pro_spec'  =>  '规格',
    'Pro_use_times'  =>  '使用次数',
    'TProAmount'  =>  '价格',
    'Pro_price'  =>  '单次售价',
    'Pro_local_amount'  =>  '本地售价',
    'Pro_local_price'  =>  '本地单价',
    'Pro_min_price'  =>  '最低售价',
    'Pro_cost'  =>  '项目成本',
    'Deduct_addr'  =>  '划扣地点',
    'Dept_id'  =>  '结算科室',
    'Deduct info'  =>  '划扣信息',

    'Project list'  =>  '项目列表',
    'Item_qty'  =>  '数量',
    'Row_total'  =>  '总价',
    'Item_total'  =>  '折后总价',
    'Discount_percent'  =>  '折扣率(%)',
    'Customer'  =>  '顾客',


    'Type_project'  =>  '项目',
    'Type_product_1'  =>  '药品',
    'Type_product_2'  =>  '物品',

    'Id'  =>  'ID',
    'Order_item_id'  =>  '项目项',
    'Deduct_times'  =>  '划扣次数',
    'Deduct amount'  =>  '划扣金额',
    'Deduct benefit amount'  =>  '本次收益',

    'Order_type_project'  =>  '项目单',
    'Order_type_product_1'  =>  '处方单',
    'Order_type_product_2'  =>  '产品单',
    'Undeliveried info'  =>  '未出库信息',
    'Undeliveried list'  =>  '未出库列表',
    'There is %s undeliveried items.'  =>  '有 %s 项未出库',
    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_PENGING  =>  '未发药',
    'deduct_status_' . \app\admin\model\DeductRecords::STATUS_COMPLETED  =>  '已发药',
];
