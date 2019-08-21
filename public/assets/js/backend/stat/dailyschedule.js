define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'stat/dailyschedule/index',
                    add_url: 'stat/dailyschedule/add',
                    edit_url: 'stat/dailyschedule/edit',
                    del_url: 'stat/dailyschedule/del',
                    multi_url: 'stat/dailyschedule/multi',
                    table: 'daily_schedule',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'sch_id',
                sortName: 'sch_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'sch_id', title: __('Sch_id')},
                        {field: 'type', title: __('Type')},
                        {field: 'stat_date', title: __('Stat_date')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
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