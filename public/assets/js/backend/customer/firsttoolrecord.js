define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/firsttoolrecord/index',
                    add_url: 'customer/firsttoolrecord/add',
                    edit_url: 'customer/firsttoolrecord/edit',
                    multi_url: 'customer/firsttoolrecord/multi',
                    table: 'first_tool_apply_records',
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
                        field: 'ctm_name',
                        title: __('customer_name')
                    }, {
                        field: 'customer_id',
                        title: __('customer_id')
                    }, {
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
                        title: __('Updatetime'),
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
                                    url: 'customer/firsttoolrecord/edit',
                                    data: {
                                        status: 'acceptapply',
                                        applyRecId: row.rec_id
                                    }
                                },ajaxSuccess);
                            },
                            'click .btn-denyone': function(e, value, row, index) {
                                e.stopPropagation();
                                Fast.api.ajax({
                                    url: 'customer/firsttoolrecord/edit',
                                    data: {
                                        status: 'denyapply',
                                        applyRecId: row.rec_id
                                    }
                                },ajaxSuccess);
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
                    rec_id: '=',
                    customer_id: '=',
                    reply_status: '=',
                    createtime: 'BETWEEN',
                    updatetime: 'BETWEEN',
                    'customer.ctm_name': 'LIKE %...%',
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