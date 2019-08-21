define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/operatebook/index',
                    add_url: 'base/operatebook/add',
                    edit_url: 'base/operatebook/edit',
                    del_url: 'base/operatebook/del',
                    multi_url: 'base/operatebook/multi',
                    table: 'operate_book',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'obk_id',
                sortName: 'obk_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'obk_id', title: __('Obk_id')},
                        {field: 'customer_id', title: __('Customer_id')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'order_item_id', title: __('Order_item_id')},
                        {field: 'order_item_name', title: __('Order_item_name')},
                        {field: 'obk_date', title: __('Obk_date')},
                        {field: 'obk_status', title: __('Obk_status'), formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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