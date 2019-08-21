define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/deductconfig/index',
                    add_url: 'base/deductconfig/add',
                    edit_url: 'base/deductconfig/edit',
                    del_url: 'base/deductconfig/del',
                    multi_url: 'base/deductconfig/multi',
                    table: 'deduct_config',
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
                    [{
                            checkbox: true
                        }, {
                            field: 'id',
                            title: __('Id')
                        },
                        // {field: 'type', title: __('Type')},
                        // {field: 'type', title: __('Type'), formatter: function(value, row, index, custome) {
                        //     return __('Pro_type_' + value);
                        // }},
                        {
                            field: 'name',
                            title: __('Name')
                        }, {
                            field: 'percent',
                            title: __('Percent')
                        }, {
                            field: 'use_when_project',
                            title: '项目中使用',
                            formatter: function(value) {
                                return value ? '<i class="fa fa-check text-success"></i>' : '';
                            },
                        }, {
                            field: 'use_when_product',
                            title: '物资中使用',
                            formatter: function(value) {
                                return value ? '<i class="fa fa-check text-success"></i>' : '';
                            },
                        }, {
                            field: 'use_when_medicine',
                            title: '药品中使用',
                            formatter: function(value) {
                                return value ? '<i class="fa fa-check text-success"></i>' : '';
                            },
                        }, {
                            field: 'is_multi',
                            title: __('Is_multi'),
                            formatter: Backend.api.formatter.status
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
            Controller.api.bindSwitchEvent();
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindSwitchEvent();
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
                $('#status-switch').bootstrapSwitch({
                    onText: "是",
                    offText: "否",
                    onColor: "success",
                    offColor: "danger",
                    size: "small",
                    //初始开关状态
                    state: $('#c-is_multi').val() == 1 ? true : false,
                    onSwitchChange: function(event, state) {
                        if (state == true) {
                            $('#c-is_multi').val(1);
                        } else {
                            $('#c-is_multi').val(0);
                        }
                    }
                })
            },
            bindSwitchEvent: function() {
                for (let tag of ['use_when_project', 'use_when_product', 'use_when_medicine']) {
                    let identifier1 = '#switch-' + tag;
                    let identifier2 = '#c-' + tag;
                    $(identifier1).bootstrapSwitch({
                        onText: "是",
                        offText: "否",
                        onColor: "success",
                        offColor: "danger",
                        size: "small",
                        //初始开关状态
                        state: $(identifier2).val() == 1 ? true : false,
                        onSwitchChange: function(event, state) {
                            if (state == true) {
                                $(identifier2).val(1);
                            } else {
                                $(identifier2).val(0);
                            }
                        }
                    });
                }
            }
        }
    };
    return Controller;
});