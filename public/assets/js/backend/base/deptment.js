define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    $.jstree.core.prototype.get_all_checked = function (full) {
        var obj = this.get_selected(), i, j;
        for (i = 0, j = obj.length; i < j; i++) {
            obj = obj.concat(this.get_node(obj[i]).parents);
        }
        obj = $.grep(obj, function (v, i, a) {
            return v != '#';
        });
        obj = obj.filter(function (itm, i, a) {
            return i == a.indexOf(itm);
        });
        return full ? $.map(obj, $.proxy(function (i) {
            return this.get_node(i);
        }, this)) : obj;
    };

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/deptment/index',
                    add_url: 'base/deptment/add',
                    edit_url: 'base/deptment/edit',
                    del_url: 'base/deptment/del',
                    multi_url: 'base/deptment/multi',
                    table: 'deptment',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'id',
                escape: false,
                pagination: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Dept_id')},
                        // {field: 'pid', title: __('Dept_pid')},
                        {field: 'dept_code', title: __('Dept_code')},
                        {field: 'name', title: __('Dept_name'), align: 'left'},
                        {field: 'dept_type', title: __('dept_type'), formatter: function (value, row, index) {
                            if (value != 0) {
                                return __('dept_type_' + value);
                            } else {
                                return '--';
                            }
                        }},
                        {field: 'dept_status', title: __('Dept_status'), formatter: Backend.api.formatter.status},
                        {field: 'dept_f_status', title: __('dept_f_status'), formatter: Backend.api.formatter.status},
                        // {field: 'dept_sort', title: __('Dept_sort')},
                        {field: 'dept_remark', title: __('Dept_remark')},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
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

    $('#status-switch').bootstrapSwitch({
        onText:"正常",
        offText:"禁用",
        onColor:"success",
        offColor:"danger",
        size:"small",
        //初始开关状态
        state: $('#c-dept_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){
                $('#c-dept_status').val(1);
            }else{
                $('#c-dept_status').val(0);
            }
        }
    })
    $('#f-status-switch').bootstrapSwitch({
        onText:"显示",
        offText:"隐藏",
        onColor:"success",
        offColor:"danger",
        size:"small",
        //初始开关状态
        state: $('#c-dept_f_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){
                $('#c-dept_f_status').val(1);
            }else{
                $('#c-dept_f_status').val(0);
            }
        }
    })
    return Controller;
});