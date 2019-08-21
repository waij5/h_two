define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'stat/customermonthlystat/index',
                    add_url: 'stat/customermonthlystat/add',
                    edit_url: 'stat/customermonthlystat/edit',
                    del_url: 'stat/customermonthlystat/del',
                    multi_url: 'stat/customermonthlystat/multi',
                    table: 'month_customer_stat',
                }
            });
            var table = $("#table");
            var currentFilter = '';
            var currentOp = '';
            // $(window).on
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rec_id',
                sortName: 'rec_id',
                commonSearch: false,
                search: false,
                escape: false,
                height: ($(window).height() - 280),
                columns: [
                    [{
                            checkbox: true
                        },
                        // {field: 'rec_id', title: __('Rec_id')},
                        {
                            field: 'rec_id',
                            title: __('No.'),
                            formatter: function(value, row, index) {
                                return index + 1;
                            }
                        }, {
                            field: 'customer_id',
                            title: __('Customer_id')
                        }, {
                            field: 'customer_name',
                            title: __('Customer_name'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'depositamt',
                            title: __('Depositamt')
                        }, {
                            field: 'last_depositamt',
                            title: __('last_depositamt')
                        },
                        // {field: 'deposit_inc', title: __('Deposit_inc')},
                        // {field: 'deposit_dec', title: __('Deposit_dec')},
                        {
                            field: 'deposit_change',
                            title: __('Deposit_change')
                        }, {
                            field: 'last_deposit_change',
                            title: __('last_deposit_change')
                        }, {
                            field: 'undeducted_total',
                            title: __('Undeducted_total')
                        }, {
                            field: 'last_undeducted_total',
                            title: __('last_undeducted_total')
                        }, {
                            field: 'not_out_total',
                            title: __('Not_out_total')
                        }, {
                            field: 'last_not_out_total',
                            title: __('last_not_out_total')
                        }, {
                            field: 'deducted_total',
                            title: __('Deducted_total')
                        }, {
                            field: 'last_deducted_total',
                            title: __('last_deducted_total')
                        }, {
                            field: 'deducted_benefit_total',
                            title: __('Deducted_benefit_total')
                        }, {
                            field: 'last_deducted_benefit_total',
                            title: __('last_deducted_benefit_total')
                        }, {
                            field: 'rank_points',
                            title: __('Rank_points')
                        }, {
                            field: 'last_rank_points',
                            title: __('last_rank_points')
                        }, {
                            field: 'pay_points',
                            title: __('Pay_points')
                        }, {
                            field: 'last_pay_points',
                            title: __('last_pay_points')
                        }, {
                            field: 'rank_points_change',
                            title: __('Rank_points_change')
                        }, {
                            field: 'last_rank_points_change',
                            title: __('last_rank_points_change')
                        }, {
                            field: 'pay_points_change',
                            title: __('Pay_points_change')
                        }, {
                            field: 'last_pay_points_change',
                            title: __('last_pay_points_change')
                        },
                        /*
                                                {
                                                    field: 'affiliate',
                                                    title: __('Affiliate')
                                                }, {
                                                    field: 'last_affiliate',
                                                    title: __('last_affiliate')
                                                }, {
                                                    field: 'affiliate_change',
                                                    title: __('Affiliate_change')
                                                }, {
                                                    field: 'last_affiliate_change',
                                                    title: __('last_affiliate_change')
                                                },
                                                */
                        {
                            field: 'item_original_pay_total',
                            title: __('Item_original_pay_total')
                        }, {
                            field: 'last_item_original_pay_total',
                            title: __('last_item_original_pay_total')
                        },
                        // {field: 'item_pay_total', title: __('Item_pay_total')},
                        // {field: 'last_item_pay_total', title: __('last_item_pay_total')},
                        // {field: 'item_real_pay_total', title: __('Item_real_pay_total')},
                        // {field: 'last_item_real_pay_total', title: __('last_item_real_pay_total')},
                        {
                            field: 'item_switch_total',
                            title: __('Item_switch_total')
                        }, {
                            field: 'last_item_switch_total',
                            title: __('last_item_switch_total')
                        }, {
                            field: 'balance_total',
                            title: __('Balance_total')
                        }, {
                            field: 'last_balance_total',
                            title: __('last_balance_total')
                        }, {
                            field: 'period_balance_total',
                            title: __('period_balance_total')
                        }, {
                            field: 'stat_date',
                            title: __('Stat_date')
                        },
                        // {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                    ]
                ],
                onLoadSuccess: function(data) {
                    $("[data-toggle='tooltip']").tooltip();
                    if (data.summary) {
                        for (var i in data.summary) {
                            $('#h-' + i).text(data.summary[i]);
                        }
                    }
                },
                onRefresh: function(params) {
                    if (params) {
                        currentFilter = params.query.filter;
                        currentOp = params.query.op;
                    }
                },
            });
            $(window).resize(function() {
                table.bootstrapTable('resetView');
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                event.stopPropagation();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    // customer_id: '=',
                    // customer_name: 'LIKE %...%',
                    // depositamt: 'BETWEEN',
                    // undeducted_total: 'BETWEEN',
                    // not_out_total: 'BETWEEN',
                    // deducted_total: 'BETWEEN',
                    // deducted_benefit_total: 'BETWEEN',
                    // rank_points: 'BETWEEN',
                    // pay_points: 'BETWEEN',
                    // affiliate: 'BETWEEN',
                    // item_original_pay_total: 'BETWEEN',
                    // item_switch_total: 'BETWEEN',
                    // balance_total: 'BETWEEN',
                    stat_date: 'BETWEEN',
                });
            });
            $('#btn-export').on('click', function() {
                if (currentFilter == '') {
                    Toastr.error('请输入正确查询条件');
                    return;
                }
                var url = '/stat/customermonthlystat/downloadprocess?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('stat/customermonthlystat/downloadprocess');
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});