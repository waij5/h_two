define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/customertype/index',
                    add_url: 'base/customertype/add',
                    edit_url: 'base/customertype/edit',
                    del_url: 'base/customertype/del',
                    multi_url: 'base/customertype/multi',
                    table: 'customertype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'type_id',
                search: false,
                sortName: 'type_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'type_id', title: __('type_id')},
                        {field: 'type_name', title: __('type_name')},
                        {field: 'type_remark', title: __('type_remark')},
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