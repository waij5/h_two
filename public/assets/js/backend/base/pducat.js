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
                    index_url: 'base/pducat/index',
                    add_url: 'base/pducat/add',
                    edit_url: 'base/pducat/edit',
                    del_url: 'base/pducat/del',
                    multi_url: 'base/pducat/multi',
                    table: 'pducat',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'pdc_id',
                sortName: 'pdc_id',
                escape: false,
                search: false,
                pagination: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'pdc_id', title: __('Pdc_id')},
                        {field: 'pdc_code', title: __('Pdc_code')},
                        {field: 'pdc_name', title: __('Pdc_name'), align: 'left'},
                        {field: 'pdc_zpttype', title: __('Pdc_zpttype')},
                        {field: 'pdc_pid', title: __('Pdc_pid')},
                        {field: 'pdc_status', title: __('Pdc_status'), formatter: Backend.api.formatter.status},
                        // {field: 'pdc_sort', title: __('Pdc_sort')},
                        {field: 'pdc_remark', title: __('Pdc_remark')},
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
        state: $('#c-pdc_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-pdc_status').val(1);
            }else{  
                $('#c-pdc_status').val(0);
            }  
        }  
    })

    return Controller;
});