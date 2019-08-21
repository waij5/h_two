define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'bootstrap-select'], function($, undefined, Backend, Table, Form, bootstrapSelect) {
    var Controller = {
        index: function() {
            var table = $('#table');
            var currentFilter = '';
            var currentOp = '';
            Table.api.init({});
            table.bootstrapTable({
                url: '/stat/customerorderitems/index',
                commonSearch: false,
                search: false,
                escape: false,
                sortName: 'customer_id',
                sortOrder: 'ASC',
                columns: [
                    [{
                        field: 'customer_id',
                        title: __('Customer'),
                        formatter: function(value, row, index) {
                            return '<' + value + '>' + row.ctm_name;
                        },
                        cellStyle: function() {
                            return {
                                css: {
                                    'width': '145px',
                                    'word-wrap': 'break-word',
                                    'text-align': 'left !important',
                                },
                            }
                        },
                    }, {
                        field: 'ctm_depositamt',
                        title: __('Deposit Amount')
                    }, {
                        field: 'develop_admin_name',
                        title: __('Develop admin'),
                        function(value, row, index) {
                            if (row.develop_admin_id && typeof yjyBriefAdminList[row.develop_admin_id] != 'undefined') {
                                return yjyBriefAdminList[row.develop_admin_id];
                            }
                        }
                    }, {
                        field: 'item_count',
                        title: '<span title="含退换产生的订单" data-toggle="tooltip" data-placement="bottom">订购项目数:</span>'
                    }, {
                        field: 'item_original_pay_total',
                        title: '<span title="原始支付额(含变动)" data-toggle="tooltip" data-placement="bottom">' + __('item_original_pay_total') + '<i class="fa fa-question-circle-o"></i></span>',
                    }, {
                        field: 'item_switch_total',
                        title: '<span title="本期变动额" data-toggle="tooltip" data-placement="bottom">本期变动额<i class="fa fa-question-circle-o"></i></span>',
                    }, {
                        field: 'item_pay_total',
                        title: '<span title="现实际支付额(与原始支付额不一定相同，因为可能在此段时间后另有退换项目)" data-toggle="tooltip" data-placement="bottom">' + __('item_pay_total') + '<i class="fa fa-question-circle-o"></i></span>',
                    }, {
                        field: 'undeducted_total',
                        title: __('undeducted_total'),
                    }, {
                        field: 'item_coupon_total',
                        title: '<span title="使用券额(不包含在支付额内的部分)" data-toggle="tooltip" data-placement="bottom">' + __('item_coupon_total') + '<i class="fa fa-question-circle-o"></i></span>',
                    }, ]
                ],
                onLoadSuccess: function(data) {
                    if (data.summary) {
                        for (var i in data.summary) {
                            $('.h-summary-block #s-' + i).text(data.summary[i]);
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
            Table.api.bindevent(table);
            $("[data-toggle='tooltip']").tooltip();
            Controller.api.bindevent();
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'customer.ctm_id': '=',
                    'customer.admin_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': '=',
                    'order_items.item_paytime': 'BETWEEN',
                });
            });
            $('#btn-export').on('click', function() {
                var url = '/stat/customerorderitems/downloadprocess' + '?type=index&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        changedetails: function() {
            var currentOp = '';
            var currentFilter = '';
            Table.api.init();
            var table = $("#table");
            $type = 'changedetails';
            // 初始化表格
            table.bootstrapTable({
                url: '/stat/customerorderitems/changedetails',
                commonSearch: false,
                search: false,
                escape: false,
                sortName: 'createtime',
                sortOrder: 'ASC',
                columns: [
                    [{
                        field: 'customer_id',
                        title: 'No.',
                        formatter: function(value, row, index) {
                            return index + 1;
                        }
                    }, {
                        field: 'createtime',
                        title: __('change time'),
                        formatter: Backend.api.formatter.date
                    }, {
                        field: 'customer_id',
                        title: __('customer_id')
                    }, {
                        field: 'ctm_name',
                        title: __('Customer')
                    }, {
                        field: 'change_type',
                        title: __('change type'),
                        formatter: function(value) {
                            return __(value);
                        }
                    }, {
                        field: 'original_item_data',
                        title: __('old item data'),
                        formatter: function(value, row, index) {
                            value = JSON.parse(value);
                            if (typeof value.item_id != 'undefined') {
                                var ori_pro = '<div><label style="width:50%" class="text-right">项目名:&nbsp;&nbsp; </label><label style="text-align:left;width:50%;font-weight:normal"> ' + value.pro_name + '</label></div>';
                                ori_pro += '<div><label style="width:50%" class="text-right">规格:&nbsp;&nbsp; </label><label style="text-align:left;width:50%;font-weight:normal"> ' + value.pro_spec + '/' + value.item_total_times + '</label></div>';
                                ori_pro += '<div><label style="width:50%" class="text-right">已使用次数:&nbsp;&nbsp; </label><label style="text-align:left;width:50%;font-weight:normal"> ' + value.item_used_times + '/' + value.item_total_times + '</label></div>';
                                ori_pro += '<div><label style="width:50%" class="text-right">支付金额:&nbsp;&nbsp; </label><label style="text-align:left;width:50%;font-weight:normal"> ' + value.item_pay_total + '</label></div>';
                                ori_pro += '<div><label style="width:50%" class="text-right">已划扣金额:&nbsp;&nbsp; </label><label style="text-align:left;width:50%;font-weight:normal"> ' + (value.item_pay_amount_per_time * value.item_used_times).toFixed(2) + '</label></div>'
                                return ori_pro;
                            }
                        },
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    'width': '20%',
                                }
                            }
                        },
                    }, {
                        field: 'new_item_data',
                        title: __('new item data'),
                        formatter: function(value, row, index) {
                            var tmpRow = '<table class="table table-bordered table-condensed" style="border-bottom:0"><thead style="display: table-header-group;"><tr><th style="text-align: center; vertical-align: middle; "><div class="th-inner ">项目名</div><div class="th-inner "></div></th><th style="text-align: center; vertical-align: middle; "><div class="th-inner ">规格</div><div class="th-inner "></div></th><th style="text-align: center; vertical-align: middle; ">使用次数</th><th style="text-align: center; vertical-align: middle; ">支付金额</th></tr></thead><tbody>';
                            value = JSON.parse(value);
                            for (var i in value) {
                                tmpRow += '<tr><td style="width:25%">' + value[i]['pro_name'] + '</td><td style="width:25%">' + value[i]['pro_spec'] + '</td><td style="width:15%">' + value[i]['item_used_times'] + '/' + value[i]['item_total_times'] + '</td><td style="width:30%">' + value[i]['item_pay_total'] + '</td></tr>'
                                //                              tmpRow += 'pro_name: ' + value[i]['pro_name'] + '<br />';
                                //                              tmpRow += 'pro_name: ' + value[i]['item_used_times']+'/' +value[i]['item_total_times']+ '<br />';
                                //                              tmpRow += 'pro_name: ' + value[i]['item_cost'] + '<br />';
                            }
                            tmpRow += '</tbody></table>'
                            return tmpRow;
                        },
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    'padding': '0',
                                }
                            }
                        },
                    }, {
                        field: 'deposit_change',
                        title: __('deposit_change')
                    }, {
                        field: 'develop_admin_name',
                        title: __('Develop admin id')
                    }, {
                        field: 'osconsult_admin_name',
                        title: __('osconsult_admin_name')
                    }, {
                        field: 'recept_admin_name',
                        title: __('Recept admin id')
                    }, {
                        field: 'operator',
                        title: __('Operator')
                    }, ]
                ],
                onLoadSuccess: function(data) {
                    $('#deposit_count').html(data.summary.count);
                    $('#deposit_change').html(data.summary.deposit_change);
                },
                onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },
            });
            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });
            $('button[type="reset"]').click(function() {
                $('.bootstrap-select').each(function(index) {
                    var searchId = $(this).find('.selectpicker').attr('name');
                    var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
                    $(this).find('.dropdown-toggle').attr('title', defaultVal).attr('data-id', searchId).removeClass('bs-placeholder');
                    $(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
                    $(this).find('.inner').find('li').eq(0).addClass('selected active');
                    $(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
                    $(this).find('.inner').find('li').removeClass('hidden');
                })
            })
            Form.events.datetimepicker($('.form-commonsearch'));
            Table.api.bindevent(table);
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'order_change_log.customer_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'order_change_log.type': '=',
                    'order_change_log.change_type': '=',
                    'order_change_log.createtime': 'BETWEEN',
                });
            });
            $('#btn-export').on('click', function() {
                var url = '/stat/customerorderitems/download?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        details: function() {
            return Controller.renderDetails();
        },
        detailsfordevelop: function() {
            return Controller.renderDetails('develop');
        },
        detailsforosconsult: function() {
            return Controller.renderDetails('osconsult');
        },
        detailsfordeductdept: function() {
            return Controller.renderDetails('deductdept');
        },
        cashdetails: function() {
            Table.api.init({
            });
            var currentOp = '';
            var currentFilter = '';
            var table = $("#table");
            var baseUrl = window.location.origin;
            var ReceiptAId = $('#receipt_a_id').val();
            // 初始化表格
            table.bootstrapTable({
                url: 'stat/customerorderitems/cashdetails',
                pk: 'balance_id',
                sortName: 'balance_id',
                search: false,
                commonSearch: false,
                escape: false,
                height: ($(window).height() - 200),
                columns: [
                    [
                        // {
                        //     field: 'balance_id',
                        //     title: '流水号',
                        // },
                        {
                            field: 'createtime',
                            title: '付款时间',
                            formatter: Table.api.formatter.datetime,
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "140px",
                                        "word-break": "keep-all",
                                    }
                                }
                            },
                        },
                        {
                            field: 'customer_id',
                            title: '卡号',
                        }, {
                            field: 'ctm_name',
                            title: '姓名',
                        },
                        {
                            field: 'b_osc_type_name',
                            title: '初复次',
                        },
                        {
                            field: 'pay_total',
                            title: '<span class="text-success" title="现金+卡+微信+支付宝+其他收款" data-toggle="tooltip">支付额<i class="fa fa-question-circle-o"></i></span>'
                        },
                        {
                            field: 'customer_admin_name',
                            title: __('yjy_developer_name')
                        }, {
                            field: 'last_osc_admin',
                            title: __('osconsult_admin_name')
                        }, {
                            field: 'customer_admin_dept',
                            title: '营销部门',
                        },
                        // {
                        //     field: 'admin_id',
                        //     title: '收银',
                        // },
                        {
                            field: 'explore_name',
                            title: '营销渠道',
                        }, 
                        {
                            field: 'source_name',
                            title: '客户来源',
                        }, 
                        {
                            field: 'tool_name',
                            title: '首次受理工具',
                        },
                        {
                            field: 'balance_type',
                            title: '类型',
                        },
                        {
                            field: 'refund_type_name',
                            title: '退款类型',
                        }, {
                            field: 'balance_remark',
                            title: __('Memo'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "120px",
                                        "word-wrap": "normal",
                                        'text-align': 'left !important',
                                    }
                                }
                            },
                        },
                    ]
                ],
                onLoadSuccess: function(data) {
                    if (data.summary) {
                        for (var i in data.summary) {
                            $('#b_' + i).length && $('#b_' + i).text(data.summary[i]);
                        }
                    }
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentOp = params.query.op;
                        currentFilter = params.query.filter;
                    }
                },
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

             // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:5px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            $('.offWrap').click(function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'balance.createtime': 'BETWEEN',
                    'customer.ctm_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'balance.balance_type': '=',
                    'balance.refund_type': '=',
                    'admin.dept_id': '=',
                    'admin.id': '=',
                    'customer.ctm_last_osc_admin': '=',
                    'balance.admin_id': '=',
                    'customer.admin_id': '=',
                    'customer.ctm_source': '=',
                    'customer.ctm_explore': '=',
                    'customer.ctm_first_tool_id': '=',
                    'balance.b_osc_type': '=',

                });
            });

            //导出
            $('#btn-export').on('click', function() {
                var url = '/stat/customerorderitems/cashdetailsdownload' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        cashdetailsdownload: function() {
            return Backend.api.commondownloadprocess('stat/customerorderitems/cashdetailsdownload');
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('stat/customerorderitems/downloadprocess');
        },
        download: function() {
            return Backend.api.commondownloadprocess('stat/customerorderitems/download');
        },
        renderDetails: function($type) {
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({});
            var table = $("#table");
            switch ($type) {
                case 'develop':
                    var indexUrl = 'stat/customerorderitems/detailsfordevelop';
                    break;
                case 'osconsult':
                    var indexUrl = 'stat/customerorderitems/detailsforosconsult';
                    break;
                case 'deductdept':
                    var indexUrl = 'stat/customerorderitems/detailsfordeductdept';
                    break;
                default:
                    $type = 'details';
                    var indexUrl = 'stat/customerorderitems/details';
                    break;
            }
            // 初始化表格
            table.bootstrapTable({
                url: indexUrl,
                commonSearch: false,
                search: false,
                //              searchOnEnterKey: false,
                escape: false,
                height: ($(window).height() - 150),
                pageSize: 50,
                columns: [
                    [{
                            field: 'customer_id',
                            title: 'No.',
                            formatter: function(value, row, index) {
                                return index + 1;
                            }
                        }, {
                            field: 'item_paytime',
                            title: __('Item pay time'),
                            formatter: Backend.api.formatter.date
                        }, {
                            field: 'customer_id',
                            title: __('Customer id')
                        }, {
                            field: 'ctm_name',
                            title: __('Customer')
                        }, {
                            field: 'pro_name',
                            title: __('Pro name'),
                        }, {
                            field: 'pro_spec',
                            title: __('Pro spec')
                        }, {
                            field: 'ctm_first_cpdt',
                            title: __('ctm_first_cpdt_id')
                        },
                        //                      划扣科室
                        {
                            field: 'dept_name',
                            title: __('Deduct dept')
                        }, {
                            field: 'item_used_times',
                            title: __('item_used_times')
                        }, {
                            field: 'item_total_times',
                            title: __('item_total_times')
                        },
                        // {
                        //     field: 'item_amount_per_time',
                        //     title: __('Item amount pertime')
                        // },
                        {
                            field: 'item_total',
                            title: __('Item total')
                        }, {
                            field: 'item_pay_total',
                            title: '<span title="' + __('amount without coupon') + '">' + __('Item cash total') + '<i class="fa fa-question-circle-o"></i></span>'
                        }, {
                            field: 'item_undeducted_total',
                            title: __('Item undeduct total')
                        },
                        // {
                        //     field: 'item_original_total',
                        //     title: __('Item original total')
                        // },
                        {
                            field: 'item_original_pay_total',
                            title: __('Item original pay total')
                        }, {
                            field: 'consult_admin_name',
                            title: '<span class="text-success" title="开单时的网络客服" data-toggle="tooltip">网络客服<i class="fa fa-question-circle-o"></i></span>'
                        },
                        // {
                        //     field: 'develop_admin_name',
                        //     title: '<span title="顾客现在的网络客服" data-toggle="tooltip">网络客服<i class="fa fa-question-circle-o text-success"></i></span>'
                        // }, 
                        {
                            field: 'ctm_explore',
                            title: __('ctm_explore')
                        }, {
                            field: 'ctm_source',
                            title: __('ctm_source')
                        }, {
                            field: 'osconsult_admin_name',
                            title: __('osconsult_admin_name')
                        }, {
                            field: 'recept_admin_name',
                            title: __('Recept admin id')
                        }, {
                            field: 'prescriber_name',
                            title: __('prescriber_name')
                        }, {
                            field: 'ctm_first_tool',
                            title: __('ctm_first_tool')
                        }, {
                            field: 'osc_type',
                            title: __('osc_type'),
                            formatter: function(value, row, index) {
                                if (value) {
                                    return __('osc_type_' + value);
                                }
                            }
                        }, {
                            field: 'ctm_first_recept_time',
                            title: __('ctm_first_recept_time'),
                            formatter: Backend.api.formatter.datetime,
                        }, {
                            field: 'item_type_name',
                            title: __('item_type')
                        },
                    ]
                ],
                onLoadSuccess: function(data) {
                    $('[data-toggle="tooltip"]').tooltip();
                    if (data.summary && typeof data.summary.item_pay_total != 'undefined') {
                        $('#total').html(data.summary.item_pay_total ? data.summary.item_pay_total : 0);
                        $('#count').html(data.summary.count ? data.summary.count : 0);
                        $('#uniq_customer_count').html(data.summary.uniq_customer_count ? data.summary.uniq_customer_count : 0);
                        $('#item_used_pay_total').html(data.summary.deducted_total ? data.summary.deducted_total : 0);
                        $('#unused_total').html(data.summary.undeducted_total ? data.summary.undeducted_total : 0);
                        $('#total_times').html(data.summary.total_times ? data.summary.total_times : 0);
                        $('#used_total_times').html(data.summary.used_total_times ? data.summary.used_total_times : 0);
                        $('#item_original_pay_total').html(data.summary.item_original_pay_total ? data.summary.item_original_pay_total : 0);
                    }
                },
                onRefresh: function(params) {
                    if (params) {
                        currentFilter = params.query.filter;
                        currentOp = params.query.op;
                    }
                },
            });
            if ($('#btn-view-cash-total').length) {
                $('#btn-view-cash-total').on('click', function() {
                    let cashTotalLayer = Fast.api.open('stat/customerorderitems/cashdetails', '收款业绩');
                    let cashLayerMaxBtn = $('#layui-layer' + cashTotalLayer + ' .layui-layer-max');
                    if (cashLayerMaxBtn.length) {
                        cashLayerMaxBtn.trigger('click');
                    }
                })
            }
            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });
            $('button[type="reset"]').click(function() {
                $('.bootstrap-select').each(function(index) {
                    var searchId = $(this).find('.selectpicker').attr('name');
                    var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
                    $(this).find('.dropdown-toggle').attr('title', defaultVal).attr('data-id', searchId).removeClass('bs-placeholder');
                    $(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
                    $(this).find('.inner').find('li').eq(0).addClass('selected active');
                    $(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
                    $(this).find('.inner').find('li').removeClass('hidden');
                })
            })
            Form.events.datetimepicker($('.form-commonsearch'));
            Table.api.bindevent(table);
            // 类别
            $(document).on("change", "select[name='project.pro_cat1']", function() {
                var cate = $('[name="project.pro_cat1"]').val();
                var tArg = arguments;
                $.ajax({
                    url: "base/project/getLv2Cate",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        cate_id: cate
                    },
                    success: function(data) {
                        $('[name="project.pro_cat2"]').html('');
                        sortData = Object.keys(data);
                        sortData.sort();
                        for (var i in sortData) {
                            $('[name="project.pro_cat2"]').append('<option value="' + sortData[i] + '">' + data[sortData[i]] + '</option>');
                        }
                    }
                });
            })
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'order_items.item_type': '=',
                    'order_items.item_change_status': '=',
                    'order_items.dept_id': '=',
                    'order_items.pro_name': 'LIKE %...%',
                    'order_items.pro_spec': 'LIKE %...%',
                    'order_items.item_paytime': 'BETWEEN',
                    'order_items.customer_id': '=',
                    'osc.osc_type': '=',
                    'order_items.consult_admin_id': '=',
                    'order_items.admin_id': '=',
                    'order_items.recept_admin_id': '=',
                    'order_items.prescriber': '=',
                    'osc.dept_id': '=',
                    'osc.cpdt_id': '=',
                    'customer.develop_dept_id': '=',
                    'customer.ctm_first_recept_time': 'BETWEEN',
                    // 'customer.admin_id': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_first_dept_id': '=',
                    'customer.ctm_first_cpdt_id': '=',
                    'customer.ctm_first_dept_id': '=',
                    'customer.ctm_first_osc_dept_id': '=',
                    'customer.ctm_first_osc_cpdt_id': '=',
                    'customer.ctm_source': '=',
                    'customer.ctm_explore': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.old_ctm_code': '=',
                    'order_items.item_status': '=',
                    'project.pro_cat1': '=',
                    'project.pro_cat2': '=',
                });
            });
            $('#btn-export').on('click', function() {
                var url = '/stat/customerorderitems/downloadprocess' + '?type=' + $type + '&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
            bindStaffSelect: function() {
                var tmpList = new Array();
                $('.nickname').keyup(function() {
                    var _this = $(this);
                    var keywords = $(this).val();
                    if (keywords == '') {
                        $('.word').empty().hide();
                        return;
                    };
                    var filter = JSON.stringify({
                        username: keywords
                    });
                    var op = JSON.stringify({
                        username: "LIKE %...%"
                    });
                    var fieldSpell = "username";
                    var username = "username";
                    $.ajax({
                        url: '/cash/order/staffquicksearch',
                        data: {
                            filter: filter,
                            op: op
                        },
                        dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                        beforeSend: function() {
                            $(_this).siblings().find('.word').append('<div>正在加载。。。</div>');
                        },
                        success: function(data) {
                            $(_this).siblings().find('.word').empty().show();
                            // console.log(data);
                            tmpList = data.rows;
                            if (data.total) {
                                for (var i in data.rows) {
                                    $(_this).siblings().find('.word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + i + '">' + data.rows[i]['nickname'] + '</li>');
                                }
                                $(_this).siblings().find('.word').show();
                            }
                        },
                        error: function() {
                            $(_this).siblings().find('.word').empty().show();
                            $(_this).siblings().find('.word').append('<div class="click_work">Fail "' + keywords + '"</div>');
                        }
                    })
                })
                $('.word').on('click', 'li', function() {
                    var i = $(this).data('index');
                    $('.nickname').val(tmpList[i]['nickname']);
                    $('#field_admin_id').val(tmpList[i]['id']);
                });
                $('#btn-admin-clear').on('click', function() {
                    $('.nickname').val('');
                    $('#field_admin_id').val('');
                });
            },
            generateRow: function(rowData, type) {
                var row = '';
                var developAdmin = rowData['develop_admin_id'] && rowData['develop_admin_id'] != '0' ? ('<' + rowData['consult_admin_id'] + '>') : '';
                if (typeof yjyBriefAdminList[rowData['develop_admin_id']] != 'undefined') {
                    developAdmin += yjyBriefAdminList[rowData['develop_admin_id']];
                }
                var pricePerTime = 0.00;
                var itemTotalTimes = parseFloat(rowData['item_total_times']);
                if (itemTotalTimes > 0) {
                    pricePerTime = (parseFloat(rowData['order_total']) / itemTotalTimes).toFixed(2);
                }
                var discountPercent = 100.00;
                var orderOriTotal = parseFloat(rowData['order_ori_total']);
                var orderTotal = parseFloat(rowData['order_total']);
                if (orderOriTotal > 0) {
                    discountPercent = (100.00 * orderTotal / orderOriTotal).toFixed(2);
                }
                // deposit_total
                // dbalance_total
                var depositTotal = parseFloat(rowData['deposit_total']);
                var dbalanceTotal = !rowData['dbalance_total'] ? 0.00 : parseFloat(rowData['dbalance_total']);
                //dbalanceTotal 订单定金变动 -- 实际顾客定金变动的 负值
                var realDepositTotal = depositTotal + dbalanceTotal;
                // (parseFloat(rowData['deposit_total']) - parseFloat(rowData['dbalance_total']))
                row += '<tr>' + '<td><' + rowData['customer_id'] + '>' + (!rowData['ctm_name'] ? __('None') : rowData['ctm_name']) + '</td>' + '<td>' + rowData['ctm_depositamt'] + '</td>' + '<td>' + developAdmin + '</td>' + '<td>' + rowData['item_count'] + '</td>' + '<td>' + rowData['item_total_times'] + '</td>' + '<td>' + pricePerTime + '</td>' + '<td>' + discountPercent + '</td>' + '<td>' + orderOriTotal + '</td>' + '<td>' + orderTotal + '</td>' + '<td>' + rowData['pay_total'] + '</td>' + '<td>' + realDepositTotal + '</td>' + '<td>' + rowData['used_coupon_total'] + '</td>' + '</tr>';
                $('#table tbody').append(row);
            }
        }
    };
    var currentCustomerId = -1;
    return Controller;
});