define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/exprtype/index',
                    add_url: 'base/exprtype/add',
                    edit_url: 'base/exprtype/edit',
                    del_url: 'base/exprtype/del',
                    multi_url: 'base/exprtype/multi',
                    table: 'exprtype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'ept_id',
                search: false,
                sortName: 'ept_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'ept_id', title: __('Ept_id')},
                        {field: 'ept_code', title: __('Ept_code')},
                        {field: 'ept_name', title: __('Ept_name')},
                        {field: 'ept_status', title: __('Ept_status'), formatter: Backend.api.formatter.status},
                        // {field: 'ept_sort', title: __('Ept_sort')},
                        {field: 'ept_remark', title: __('Ept_remark')},
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
        state: $('#c-ept_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-ept_status').val(1);
            }else{  
                $('#c-ept_status').val(0);
            }  
        }  
    })
    
    return Controller;
});