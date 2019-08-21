define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/filter/index',
                    add_url: 'base/filter/add',
                    edit_url: 'base/filter/edit',
                    del_url: 'base/filter/del',
                    multi_url: 'base/filter/multi',
                    table: 'filter',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'fat_id',
                search: false,
                sortName: 'fat_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'fat_id', title: __('Fat_id')},
                        {field: 'fat_code', title: __('Fat_code')},
                        {field: 'fat_name', title: __('Fat_name')},
                        {field: 'status', title: __('Status'), formatter: Backend.api.formatter.status},
                        // {field: 'sort', title: __('Sort')},
                        {field: 'remark', title: __('Remark')},
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
        state: $('#c-status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-status').val(1);
            }else{  
                $('#c-status').val(0);
            }  
        }  
    })

    return Controller;
});