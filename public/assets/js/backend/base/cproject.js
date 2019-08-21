define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/cproject/index',
                    add_url: 'base/cproject/add',
                    edit_url: 'base/cproject/edit',
                    del_url: 'base/cproject/del',
                    multi_url: 'base/cproject/multi',
                    table: 'c_project',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'Id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'cpdt_name', title: __('Cpdt_name')},
                        {field: 'cpdt_type', title: __('Cpdt_type')},
                        {field: 'dept_name', title: __('Dept_id')},
                        {field: 'cpdt_status', title: __('Cpdt_status'), formatter: Backend.api.formatter.status},
                        {field: 'cpdt_remark', title: __('Cpdt_remark')},
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
        state: $('#c-cpdt_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-cpdt_status').val(1);
            }else{  
                $('#c-cpdt_status').val(0);
            }  
        }  
    })

    return Controller;
});