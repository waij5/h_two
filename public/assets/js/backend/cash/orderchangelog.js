define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cash/orderchangelog/index',
                    add_url: 'cash/orderchangelog/add',
                    // edit_url: 'cash/orderchangelog/edit',
                    // del_url: 'cash/orderchangelog/del',
                    multi_url: 'cash/orderchangelog/multi',
                    table: 'order_change_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'log_id',
                sortName: 'log_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'log_id', title: __('Log_id')},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'type', title: __('Type')},
                        {field: 'balance_id', title: __('Balance_id')},
                        {field: 'old_name', title: __('Old_name')},
                        {field: 'new_name', title: __('New_name')},
                        {field: 'deposit_change', title: __('Deposit_change')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'stat_date', title: __('Stat_date')},
                        {field: 'createtitme', title: __('Createtitme')},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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