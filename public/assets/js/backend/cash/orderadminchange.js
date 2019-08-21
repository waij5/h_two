define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cash/orderadminchange/index',
                    // add_url: 'base/ctmsource/add',
                    // edit_url: 'base/ctmsource/edit',
                    // del_url: 'base/ctmsource/del',
                    // multi_url: 'base/ctmsource/multi',
                    table: 'order_admin_change',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('id')},
                        {field: 'item_id', title: __('item_id')},
                        {field: 'item_consult_old_admin_name', title: __('item_consult_old_admin')},
                        {field: 'item_consult_new_admin_name', title: __('item_consult_new_admin')},
                        {field: 'admin_id_name', title: __('admin_id')},
                        {field: 'remark', title: __('remark')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
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