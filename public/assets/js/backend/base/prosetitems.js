define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/prosetitems/index',
                    add_url: 'base/prosetitems/add',
                    edit_url: 'base/prosetitems/edit',
                    del_url: 'base/prosetitems/del',
                    multi_url: 'base/prosetitems/multi',
                    table: 'pro_set_items',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                search: false,
                pk: 'set_item_id',               
                sortName: 'set_item_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'set_item_id', title: __('Set_item_id')},
                        {field: 'pro_set_id', title: __('Pro_set_id')},
                        {field: 'set_pro_id', title: __('Set_pro_id')},
                        {field: 'set_item_amount', title: __('Set_item_amount')},
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