define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/customermap/index',
                    add_url: 'customer/customermap/add',
                    edit_url: 'customer/customermap/edit',
                    del_url: 'customer/customermap/del',
                    multi_url: 'customer/customermap/multi',
                    table: 'Customermap',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('id')},
                        {field: 'user_id', title: __('user_id')},
                        {field: 'customer_id', title: __('customer_id')},
                        {field: 'mobile', title: __('mobile')},
                        {field: 'phone', title: __('phone')},
                        {field: 'ctm_name', title: __('ctm_name')},
                        {field: 'user_name', title: __('user_name')},
                        {field: 'real_name', title: __('real_name')},
                        {field: 'nick_name', title: __('nick_name')},
                        // {field: 'rank_points', title: __('rank_points')},
                        // {field: 'pay_points', title: __('pay_points')},
                        // {field: 'deposit_amt', title: __('deposit_amt')},
                        // {field: 'status', title: __('status')},
                        {field: 'createtime', title: '合并时间',formatter: Table.api.formatter.datetime},
                        // {field: 'sync_time', title: __('sync_time')},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

   

    return Controller;
});