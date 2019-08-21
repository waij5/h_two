define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/ctmchannels/index',
                    add_url: 'base/ctmchannels/add',
                    edit_url: 'base/ctmchannels/edit',
                    del_url: 'base/ctmchannels/del',
                    multi_url: 'base/ctmchannels/multi',
                    table: 'ctmchannels',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'chn_id',
                sortName: 'chn_id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'chn_id', title: __('Chn_id')},
                        {field: 'chn_code', title: __('Chn_code')},
                        {field: 'chn_name', title: __('Chn_name')},
                        {field: 'chn_type', title: __('Chn_type'), formatter: yjyApi.formatter.chntype},
                        // {field: 'chn_uid', title: __('Chn_uid')},
                        {field: 'chn_status', title: __('Chn_status'), formatter: Backend.api.formatter.status},
                        // {field: 'chn_sort', title: __('Chn_sort')},
                        {field: 'chn_remark', title: __('Chn_remark')},
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
        state: $('#c-chn_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-chn_status').val(1);
            }else{  
                $('#c-chn_status').val(0);
            }  
        }  
    })

    var yjyApi = {
       formatter: {
            chntype: function(value) {
                if(value == ''){
                    return '';
                }
                return __('ept_' + value);
            }
       }
    };
    
    return Controller;
});