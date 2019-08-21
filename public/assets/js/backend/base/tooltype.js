define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/tooltype/index',
                    add_url: 'base/tooltype/add',
                    edit_url: 'base/tooltype/edit',
                    del_url: 'base/tooltype/del',
                    multi_url: 'base/tooltype/multi',
                    table: 'tooltype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'tool_id',
                search: false,
                sortName: 'tool_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'tool_id', title: __('tool_id')},
                        {field: 'tool_name', title: __('tool_name')},
                        {field: 'tool_sort', title: __('tool_sort')},
                        {field: 'tool_remark', title: __('tool_remark')},
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