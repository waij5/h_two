define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/operator/index',
                    add_url: 'base/operator/add',
                    edit_url: 'base/operator/edit',
                    del_url: 'base/operator/del',
                    multi_url: 'base/operator/multi',
                    table: 'operator',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [{
                            checkbox: true
                        }, {
                            field: 'id',
                            title: __('Id')
                        },
                        // {field: 'admin_id', title: __('Admin_id')},
                        {
                            field: 'name',
                            title: __('Name')
                        }, {
                            field: 'title',
                            title: __('Title')
                        }, {
                            field: 'good_at',
                            title: __('Good_at'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "25%",
                                    }
                                }
                            }
                        }, {
                            field: 'remark',
                            title: __('Remark'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "45%",
                                    }
                                }
                            }
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});