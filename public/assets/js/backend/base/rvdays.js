define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/rvdays/index',
                    add_url: 'base/rvdays/add',
                    edit_url: 'base/rvdays/edit',
                    del_url: 'base/rvdays/del',
                    multi_url: 'base/rvdays/multi',
                    table: 'rvdays',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                search: false,
                pk: 'rvd_id',
                sortName: 'rvd_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'rvd_id', title: __('Rvd_id')},
                        {field: 'rvplan_id', title: __('Rvplan_id')},
                        {field: 'rvd_days', title: __('Rvd_days')},
                        {field: 'rvd_status', title: __('Rvd_status'), formatter: Backend.api.formatter.status},
                        {field: 'rvd_remark', title: __('Rvd_remark'), formatter: Backend.api.formatter.content},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                onLoadSuccess: function () {
                    $("[data-toggle='tooltip']").tooltip();
                }
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
                $('#rvd_status-switch').bootstrapSwitch({  
                    onText:"正常",  
                    offText:"禁用",  
                    onColor:"success",  
                    offColor:"danger",  
                    size:"small",
                    //初始开关状态
                    state: $('#c-rvd_status').val() == 1 ? true: false,
                    onSwitchChange:function(event,state){
                        if(state==true){  
                            $('#c-rvd_status').val(1);
                        }else{  
                            $('#c-rvd_status').val(0);
                        }  
                    }  
                });
                
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});