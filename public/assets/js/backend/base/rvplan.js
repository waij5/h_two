define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/rvplan/index',
                    add_url: 'base/rvplan/add',
                    edit_url: 'base/rvplan/editdetail',
                    // edit_url: 'base/rvplan/edit',
                    // edit_detail_url: 'base/rvplan/editdetail',
                    rvdays_url: 'base/rvdays/index',
                    del_url: 'base/rvplan/del',
                    multi_url: 'base/rvplan/multi',
                    table: 'rvplan',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rvp_id',
                sortName: 'rvp_id',
                sortOrder: 'ASC',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'rvp_id', title: __('Rvp_id')},
                        // {field: 'rvt_name', title: __('Rv_type')},
                        {field: 'rvp_name', title: __('Rvp_name'), formatter: Backend.api.formatter.content},
                        {field: 'rvp_status', title: __('Rvp_status'), formatter: Backend.api.formatter.status},
                        {field: 'rvp_remark', title: __('Rvp_remark')},
                        {field: 'is_deletable', title: __('Is_deletable'), formatter: Backend.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: {
                            'click .btn-editone': Table.api.events.operate['click .btn-editone'],
                            'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                            'click .btn-editdetail': function(e, value, row, index) {
                                e.stopPropagation();
                                var options = $(this).closest('table').bootstrapTable('getOptions');
                                Fast.api.open(options.extend.edit_detail_url + (options.extend.edit_detail_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row['rvp_id'], __('Edit'));
                            },
                        }, formatter: function (value, row, index) {
                            if (row['is_deletable']) {
                                operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a> ' + 
                                                '<a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a>';
                            } else {
                                operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a> ';
                            }

                            return operateHtml;
                        }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();

                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    rvp_id: '=',
                    rvtype_id: '=',
                    rvp_name: 'LIKE %...%',
                });
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        editdetail: function () {
            var planId = $('#planId').val();
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    // index_url: 'base/rvplan/index',
                    add_url: 'base/rvdays/add/planId/' + planId,
                    edit_url: 'base/rvdays/edit',
                    index_url: 'base/rvdays/index/planId/' + planId,
                    del_url: 'base/rvdays/del',
                    multi_url: 'base/rvplan/multi',
                    table: 'rvplan',
                }
            });

            var table = $("#table");
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rvd_id',
                sortName: 'rvd_days',
                sortOrder: 'ASC',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'rvtype_id', title: __('rvtype_id'),
                            formatter: function(value, row, index) {
                                if (value) {
                                    return __('rvtype_' + value);
                                } else {
                                    return '';
                                }
                            }
                        },
                        {field: 'rvd_name', title: __('rvd_name')},
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

            Controller.api.bindevent();
            Table.api.bindevent(table);
        },
        api: {
            bindevent: function () {
                $('#rvp_status-switch').bootstrapSwitch({  
                    onText:"正常",  
                    offText:"禁用",  
                    onColor:"success",  
                    offColor:"danger",  
                    size:"small",
                    //初始开关状态
                    state: $('#c-rvp_status').val() == 1 ? true: false,
                    onSwitchChange:function(event,state){
                        if(state==true){  
                            $('#c-rvp_status').val(1);
                        }else{  
                            $('#c-rvp_status').val(0);
                        }  
                    }  
                });
                $('#is_deletable-switch').bootstrapSwitch({  
                    onText:"允许",  
                    offText:"禁止",  
                    onColor:"success",  
                    offColor:"danger",  
                    size:"small",
                    //初始开关状态
                    state: $('#c-is_deletable').val() == 1 ? true: false,
                    onSwitchChange:function(event,state){
                        if(state==true){  
                            $('#c-is_deletable').val(1);
                        }else{  
                            $('#c-is_deletable').val(0);
                        }  
                    }  
                });

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});