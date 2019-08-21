define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/accountlog/index',
                    add_url: 'customer/accountlog/add',
                    edit_url: 'customer/accountlog/edit',
                    del_url: 'customer/accountlog/del',
                    multi_url: 'customer/accountlog/multi',
                    table: 'account_log',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                commonSearch: false,
                search: false,
                pk: 'log_id',
                sortName: 'log_id',
                onLoadSuccess: function(data) {
                    $("[data-toggle='tooltip']").tooltip();
                },
                columns: [
                    [{
                            checkbox: true
                        }, {
                            field: 'log_id',
                            title: __('Log_id')
                        }, {
                            field: 'ctm_name',
                            title: __('Customer_id')
                        }, {
                            field: 'customer_id',
                            title: __('Customer_id')
                        }, {
                            field: 'deposit_amt',
                            title: __('Deposit_amt')
                        },
                        {
                            field: 'coupon_amt',
                            title: __('coupon_amt')
                        },
                        // {
                        //     field: 'frozen_deposit_amt',
                        //     title: __('Frozen_deposit_amt')
                        // }, 
                        {
                            field: 'rank_points',
                            title: __('Rank_points')
                        }, {
                            field: 'pay_points',
                            title: __('Pay_points')
                        }, {
                            field: 'affiliate_amt',
                            title: __('Affiliate_amt')
                        }, {
                            field: 'change_time',
                            title: __('Change_time'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'change_date',
                            title: __('Change_date')
                        }, {
                            field: 'change_desc',
                            title: __('Change_desc'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "text-align": "left !important",
                                        "width": "320px",
                                        "word-break": "break-all",
                                    }
                                };
                            },
                        }, {
                            field: 'change_type',
                            title: __('Change_type'), formatter: function(value, row, index) {
                                return __(value)
                            },
                        }, {
                            field: 'ip',
                            title: __('Ip')
                        }, {
                            field: 'source',
                            title: __('Source')
                        }, {
                            field: 'sync_time',
                            title: __('Sync_time'),
                            formatter: function(value, index, row) {
                                if (value == 0) {
                                    return '';
                                } else {
                                    return Table.api.formatter.datetime(value, index, row);
                                }
                            }
                        },
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
             $('.offWrap').click(function(){
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            $('.searchSubmit').click(function(){
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function (event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'customer.ctm_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'account_log.change_time': 'BETWEEN',
                    'account_log.change_type': '=',
                });
            });

            //积分兑换
            $('#btn-exchange').on('click', function() {
                var params = '?mode=redirect&url=' + 'customer/accountlog/exchange' + '&field=customer_id&title=' + __('exchange');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });

             //定金/佣金/等级积分 调整
            $('#btn-adjust').on('click', function() {
                var params = '?mode=redirect&url=' + 'customer/accountlog/adjust' + '&field=customer_id&title=' + __('exchange');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
        },
        add: function() {
            Controller.api.bindevent();
        },
        exchange: function() {
            Controller.api.bindevent();
        },
        adjust: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});