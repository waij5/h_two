define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/ctmsource/index',
                    add_url: 'base/ctmsource/add',
                    edit_url: 'base/ctmsource/edit',
                    del_url: 'base/ctmsource/del',
                    multi_url: 'base/ctmsource/multi',
                    table: 'ctmsource',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'sce_id',
                search: false,
                sortName: 'sce_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'sce_id', title: __('Sce_id')},
                        {field: 'sce_code', title: __('Sce_code')},
                        {field: 'sce_name', title: __('Sce_name')},
                        {field: 'sce_spell', title: __('Sce_spell')},
                        {field: 'sce_status', title: __('Sce_status'), formatter: Backend.api.formatter.status},
                        // {field: 'sce_sort', title: __('Sce_sort')},
                        {field: 'sce_remark', title: __('Sce_remark')},
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
        state: $('#c-sce_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-sce_status').val(1);
            }else{  
                $('#c-sce_status').val(0);
            }  
        }  
    })

    return Controller;
});