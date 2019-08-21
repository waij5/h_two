define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/revisitfilter/index',
                    add_url: 'base/revisitfilter/add',
                    edit_url: 'base/revisitfilter/edit',
                    del_url: 'base/revisitfilter/del',
                    multi_url: 'base/revisitfilter/multi',
                    table: 'revisit_filter',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'filter_id',
                sortName: 'filter_sort',
                sortOrder: 'desc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'filter_name', title: __('Filter_name')},
                        {field: 'filter_remark', title: __('Filter_remark')},
                        {field: 'filter_status', title: __('Filter_status'), formatter: Backend.api.formatter.status},
                        {field: 'filter_sort', title: __('Filter_sort')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $('#filter_status-switch').bootstrapSwitch({  
                onText:"正常",  
                offText:"禁用",  
                onColor:"success",  
                offColor:"danger",  
                size:"small",
                //初始开关状态
                state: $('#c-filter_status').val() == 1 ? true: false,
                onSwitchChange:function(event,state){
                    if(state==true){  
                        $('#c-filter_status').val(1);
                    }else{  
                        $('#c-filter_status').val(0);
                    }  
                }  
            });
            Controller.api.bindevent();
        },
        edit: function () {
            $('#filter_status-switch').bootstrapSwitch({  
                onText:"正常",  
                offText:"禁用",  
                onColor:"success",  
                offColor:"danger",  
                size:"small",
                //初始开关状态
                state: $('#c-filter_status').val() == 1 ? true: false,
                onSwitchChange:function(event,state){
                    if(state==true){  
                        $('#c-filter_status').val(1);
                    }else{  
                        $('#c-filter_status').val(0);
                    }  
                }  
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