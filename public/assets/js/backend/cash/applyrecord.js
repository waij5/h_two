define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cash/applyrecord/index',
                    add_url: 'cash/applyrecord/add',
                    // edit_url: 'cash/applyrecord/edit',
                    edit_url: 'cash/order/edit',
                    // del_url: 'cash/applyrecord/del',
                    multi_url: 'cash/applyrecord/multi',
                    table: 'order_apply_records',
                }
            });
            var table = $("#table");
            var currentOp = '';
            var currentFilter = '';
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rec_id',
                sortName: 'rec_id',
                //关闭通用查询
                commonSearch: false,
                search: false,
                pk: 'rec_id',
                searchOnEnterKey: false,
                escape: false,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
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
                            field: 'rec_id',
                            title: __('Rec_id')
                        }, {
                            field: 'customer_id',
                            title: __('Ctm_id')
                        }, {
                            field: 'ctm_name',
                            title: __('customer_id'),
                            formatter: function(value, row, index) {
                                var str = '<a class = "btn-clickviewsoneInfo" title="点击查看顾客信息">' + row.ctm_name + '</a>';
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        'cursor': 'pointer',
                                        'white-space': 'nowrap',
                                    }
                                }
                            },
                            events: {
                                'click .btn-clickviewsoneInfo': function(e, value, row, index) {
                                    $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                                    Fast.api.open('/cash/order/deductview/ids/' + row.customer_id, __('Customer Info'));
                                    // $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + row.ctm_id);
                                }
                            },
                        }, {
                            field: 'item_id',
                            title: __('Order_id')
                        },
                        // {
                        //     field: 'item_id',
                        //     title: __('Order_id')
                        // },
                        {
                            field: 'apply_info',
                            title: __('Apply_info'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "350px",
                                        "word-wrap": "normal",
                                        'text-align': 'left !important',
                                    }
                                }
                            },
                        }, {
                            field: 'reply_info',
                            title: __('Reply_info'),
                            formatter: Backend.api.formatter.content
                        }, {
                            field: 'reply_status',
                            title: __('Reply_status'),
                            formatter: Controller.api.formatter.status
                        }, {
                            field: 'apply_admin_name',
                            title: __('Apply_admin_id')
                        }, {
                            field: 'reply_admin_name',
                            title: __('Reply_admin_id')
                        }, {
                            field: 'createtime',
                            title: __('Createtime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'updatetime',
                            title: '审批时间',
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            formatter: function(value, row) {
                                if (row.reply_status == 0) {
                                    return '<a href="javascript:;" class="btn btn-xs btn-success btn-acceptone" title="通过"><i class="fa fa-check"></i></a>' + ' <a href="javascript:;" class="btn btn-xs btn-danger btn-denyone" title="拒绝"><i class="fa fa-close"></i></a>';;
                                }
                            },
                            events: {
                                'click .btn-acceptone': function(e, value, row, index) {
                                    console.log('click .btn-acceptone ' + row.rec_id);
                                    e.stopPropagation();
                                    Fast.api.ajax({
                                        url: 'cash/order/edit',
                                        data: {
                                            act: 'acceptapply',
                                            applyRecId: row.rec_id
                                        },
                                    }, ajaxSuccess);
                                },
                                'click .btn-denyone': function(e, value, row, index) {
                                    e.stopPropagation();
                                    Fast.api.ajax({
                                        url: 'cash/order/edit',
                                        data: {
                                            act: 'denyapply',
                                            applyRecId: row.rec_id
                                        },
                                    }, ajaxSuccess);
                                },
                            },
                        }
                    ]
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
                    'recs.rec_id': '=',
                    'recs.item_id': '=',
                    'recs.reply_status': '=',
                    'recs.createtime': 'BETWEEN',
                    'recs.updatetime': 'BETWEEN',
                });
            });
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
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