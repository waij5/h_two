define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/rvtype/index',
                    add_url: 'base/rvtype/add',
                    edit_url: 'base/rvtype/edit',
                    del_url: 'base/rvtype/del',
                    multi_url: 'base/rvtype/multi',
                    table: 'rvtype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                search: false,
                pk: 'rvt_id',
                sortName: 'rvt_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'rvt_id', title: __('Rvt_id')},
                        {field: 'rvt_name', title: __('Rvt_name')},
                        {field: 'rvt_status', title: __('Rvt_status'), formatter: Backend.api.formatter.status},
                        {field: 'is_system', title: __('Is_system'), formatter: Backend.api.formatter.status},
                        {field: 'rvt_remark', title: __('Rvt_remark')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $('#status-switch').bootstrapSwitch({  
                onText:"正常",  
                offText:"禁用",  
                onColor:"success",  
                offColor:"danger",  
                size:"small",
                //初始开关状态
                state: $('#c-rvt_status').val() == 1 ? true: false,
                onSwitchChange:function(event,state){
                    if(state==true){  
                        $('#c-rvt_status').val(1);
                    }else{  
                        $('#c-rvt_status').val(0);
                    }  
                }
            });
            Controller.api.bindevent();
        },
        edit: function () {
            $('#status-switch').bootstrapSwitch({  
                onText:"正常",  
                offText:"禁用",  
                onColor:"success",  
                offColor:"danger",  
                size:"small",
                //初始开关状态
                state: $('#c-rvt_status').val() == 1 ? true: false,
                onSwitchChange:function(event,state){
                    if(state==true){  
                        $('#c-rvt_status').val(1);
                    }else{  
                        $('#c-rvt_status').val(0);
                    }  
                }
            });
            $('#status-is-system').bootstrapSwitch({  
                onText:"是",
                offText:"否",
                onColor:"success",
                offColor:"danger",
                size:"small",
            });
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