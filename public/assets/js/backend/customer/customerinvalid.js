define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/customerinvalid/index',
                    add_url: 'customer/customerinvalid/add',
                    edit_url: 'customer/customerinvalid/edit',
                    multi_url: 'customer/customerinvalid/multi',
                    table: 'customer_invalid_records',
                    multi_invalid_url: 'customer/customerinvalid/batchinvalid',
                }
            });
            var table = $("#table");
            var currentOp = '';
            var currentFilter = '';
             var forbiddenTrIndexes = new Array();
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'log_id',
                sortName: 'log_id',
                //关闭通用查询
                commonSearch: false,
                search: false,
                pk: 'log_id',
                searchOnEnterKey: false,
                escape: false,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();

                     var checkboxes = table.find('.bs-checkbox>input');
                $.each(forbiddenTrIndexes, function(key, value) {
                    if (checkboxes.eq(value)) {
                        checkboxes.eq(value).prop('disabled', true);
                    }
                });
                forbiddenTrIndexes = new Array();
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentOp = params.query.op;
                        currentFilter = params.query.filter;
                    }
                },
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'log_id',
                        title: __('log_id')
                    },  {field: 'ctm_name', title: __('customer_name'),formatter: function(value, row, index) {
                                var str = '<a class = "btn-clickviewsoneInfo" title="点击查看顾客信息">'+row.ctm_name+'</a>';
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        'cursor': 'pointer', 
                                        'white-space':'nowrap',
                                    }
                                }
                            },
                            events: {
                            'click .btn-clickviewsoneInfo': function (e, value, row, index) {
                                Fast.api.open('/cash/order/deductview/ids/' + row['ctm_id'],'顾客信息')
                                }
                            },
                    },{
                        field: 'customer_id',
                        title: __('customer_id')
                    }, 
                    {
                        field: 'arrive_status',
                        title: __('arrive_status'),
                        formatter: function(value) {
                                if (value == 0) {
                                    text = '<i class="fa fa-circle text-danger"></i>' + __('arrive_no');
                                } else {
                                    text = '<i class="fa fa-circle text-success"></i>' + __('arrive_yes');
                                }
                                return text;
                            }
                    }, {
                        field: 'status',
                        title: __('status'),
                        formatter: Controller.api.formatter.status
                    }, {
                        field: 'apply_admin_name',
                        title: __('Apply_admin_id')
                    }, {
                        field: 'reply_admin_name',
                        title: __('Reply_admin_id')
                    }, {
                        field: 'createtime',
                        title: __('InvalidCreatetime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'updatetime',
                        title: __('Updatetime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'operate',
                        title: __('Operate'),
                        formatter: function(value, row, index) {
                            if (row.status == 0) {
                                return '<a href="javascript:;" class="btn btn-xs btn-success btn-acceptone" title="通过"><i class="fa fa-check"></i></a>' + ' <a href="javascript:;" class="btn btn-xs btn-danger btn-denyone" title="拒绝"><i class="fa fa-close"></i></a>';;
                            }else{
                                 forbiddenTrIndexes.push(index);
                            }
                        },
                        events: {
                            // success: 
                            'click .btn-acceptone': function(e, value, row, index) {
                                e.stopPropagation();
                                Fast.api.ajax({
                                    url: 'customer/customerinvalid/edit',
                                    data: {
                                        status: 'acceptapply',
                                        applyLogId: row.log_id
                                    }
                                }, ajaxSuccess);
                            },
                            'click .btn-denyone': function(e, value, row, index) {
                                e.stopPropagation();
                                Fast.api.ajax({
                                    url: 'customer/customerinvalid/edit',
                                    data: {
                                        status: 'denyapply',
                                        applyLogId: row.log_id
                                    }
                                }, ajaxSuccess);
                            },
                        },
                    }]
                ]
            });

            function ajaxSuccess(data) {
                msg = data.msg ? __(data.msg) : __('Operation completed');
                layer.msg(msg, {
                    icon: 1
                });
                //重新获取数据并刷新显示
                table.bootstrapTable('refresh', {
                    query: {
                        op: currentOp,
                        filter: currentFilter
                    }
                });
            }
            // 为表格绑定事件
            Table.api.bindevent(table);
            // Form.events.datetimepicker($('.form-commonsearch'));
            Controller.api.bindevent();
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    // rec_id: '=',
                    'cid.status': '=',
                    'cid.customer_id': '=',
                    'cid.reply_status': '=',
                    'cid.createtime': 'BETWEEN',
                    'cid.updatetime': 'BETWEEN',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.arrive_status': '=',
                });
            });

             //批量审批
            $('#toolbar').on("click", ".btn-multiinvalid", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_invalid_url + (options.extend.multi_invalid_url.match(/(\?|&)+/) ? "&id=" : "?id=") + selectedCustomerIds, __('batch operate'));
            });

        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        batchinvalid: function() {
            Form.events.datetimepicker($('.form-commonsearch'));
            Form.events.selectpicker($("form[role=form]"));
            $('#status-sync_customer_invalid').bootstrapSwitch({
                onText: "通过",
                offText: "失败",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-sync_customer_invalid').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-sync_customer_invalid').val(1);
                    } else {
                        $('#c-sync_customer_invalid').val(0);
                    }
                }
            });
            Controller.api.bindevent();
            
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                status: function(value, row) {
                    var pre = 'reply_status_';
                    if (value < 0) {
                        value = 'm_' + Math.abs(value);
                    }
                    return __(pre + value);
                }
            }
        }
    };
    return Controller;
});