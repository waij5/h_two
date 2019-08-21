define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/pdutype/index',
                    add_url: 'base/pdutype/add',
                    edit_url: 'base/pdutype/edit',
                    del_url: 'base/pdutype/del',
                    multi_url: 'base/pdutype/multi',
                    table: 'pdutype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'pdt_id',
                search: false,
                sortName: 'pdt_sort',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'pdt_id', title: __('Pdt_id')},
                        {field: 'pdt_code', title: __('Pdt_code')},
                        {field: 'pdt_name', title: __('Pdt_name')},
                        {field: 'pdt_status', title: __('Pdt_status'), formatter: Backend.api.formatter.status},

                        // {field: 'pdt_sort', title: __('Pdt_sort')},
                        {field: 'pdt_remark', title: __('Pdt_remark')},
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
        state: $('#c-pdt_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-pdt_status').val(1);
            }else{  
                $('#c-pdt_status').val(0);
            }  
        }  
    })
    
    return Controller;
});