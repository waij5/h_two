define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/ctmeara/index',
                    add_url: 'base/ctmeara/add',
                    edit_url: 'base/ctmeara/edit',
                    del_url: 'base/ctmeara/del',
                    multi_url: 'base/ctmeara/multi',
                    table: 'ctmeara',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'ear_id',
                search: false,
                sortName: 'ear_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'ear_id', title: __('Ear_id')},
                        {field: 'ear_code', title: __('Ear_code')},
                        {field: 'ear_name', title: __('Ear_name')},
                        {field: 'ear_area', title: __('Ear_area')},
                        {field: 'ear_spell', title: __('Ear_spell')},
                        {field: 'ear_status', title: __('Ear_status'), formatter: Backend.api.formatter.status},
                        // {field: 'ear_sort', title: __('Ear_sort')},
                        {field: 'ear_remark', title: __('Ear_remark')},
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
        state: $('#c-ear_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-ear_status').val(1);
            }else{  
                $('#c-ear_status').val(0);
            }  
        }  
    })

    
    
    return Controller;
});