define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'layer'], function($, undefined, Backend, Table, Form, layer) {
    var Controller = {
        index: function() {
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
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cash/balance/index',
                    add_url: 'cash/balance/add',
                    // edit_url: 'cash/balance/edit',
                    // del_url: 'cash/balance/del',
                    // multi_url: 'cash/balance/multi',
                    order_edit_url: 'cash/order/edit',
                    item_edit_url: 'cash/order/orderitemdetail',
                    table: 'customer_balance',
                }
            });
            var currentOp = '';
            var currentFilter = '';
            var table = $("#table");
            var baseUrl = window.location.origin;
            var ReceiptAId = $('#receipt_a_id').val();
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'balance_id',
                sortName: 'balance_id',
                search: false,
                commonSearch: false,
                escape: false,
                height: ($(window).height() - 200),
                columns: [
                    [
                        // {checkbox: true},
                        {
                            field: 'balance_id',
                            title: '流水号',
                        },
                        {
                            field: 'customer_id',
                            title: __('Customer_id')
                        },
                        {
                            field: 'ctm_name',
                            title: __('Customer_name')
                        }, {
                            field: 'balance_type',
                            title: __('Balance_type')
                        }, {
                            field: 'total',
                            title: __('Total')
                        }, 
                        {
                            field: 'pay_total',
                            // title: __('Pay_total')
                             title: '<span class="text-success" title="现金+卡+微信+支付宝+其他收款" data-toggle="tooltip">支付额<i class="fa fa-question-circle-o"></i></span>'
                        },
                        {
                            field: 'cash_pay_total',
                            title: __('b_in_cash_pay_total')
                        }, {
                            field: 'card_pay_total',
                            title: __('b_in_card_pay_total')
                        }, {
                            field: 'wechatpay_pay_total',
                            title: __('b_in_wechatpay_pay_total')
                        }, {
                            field: 'alipay_pay_total',
                            title: __('b_in_alipay_pay_total')
                        }, {
                            field: 'other_pay_total',
                            title: __('b_in_other_pay_total')
                        }, 
                        {
                            field: 'deposit_total',
                            title: __('Deposit_total')
                        }, {
                            field: 'coupon_total',
                            title: __('coupon_total')
                        }, {
                            field: 'order_id',
                            title: __('Order id'),
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    e.stopPropagation();
                                    var options = $(this).closest('table').bootstrapTable('getOptions');
                                    Fast.api.open(options.extend.item_edit_url + (options.extend.item_edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row['balance_id'], __('Edit'));
                                },
                            },
                            formatter: function(value, row) {
                                // console.log(row);
                                return (row['balance_type'] == '项目收款') ? '<a href="javascript:;" class="btn-editone" title="编辑">查看订单</a>' : '--';
                            }
                        },
                        // {field: 'deptment_id', title: __('Deptment_id')},
                        // {field: 'dept_name', title: __('Deptment_id')},
                        // {field: 'rec_admin_id', title: __('Rec_admin_id')},
                        // {field: 'admin_id', title: __('Admin_id')},
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'nickname',
                            title: __('nickname')
                        }, {
                            field: 'develop_admin_name',
                            title: __('yjy_developer_name')
                        }, {
                            field: 'osc_admin_name',
                            title: __('osc_Admin_nickname')
                        }, {
                            field: 'refund_type_name',
                            title: __('refund_type')
                        }, {
                            field: 'balance_remark',
                            title: __('Balance_remark'),
                            formatter: Backend.api.formatter.content
                        },
                        /*{
                            field: 'returncoupon',
                            title: __('Return coupon'),
                            table: table,
                            events: {
                                'click .btn-return-coupon': function(e, value, row, index) {
                                    Fast.api.open('cash/balance/returncoupon/balance_id/' + row.balance_id + "?customer_id=" + row.customer_id, __('returncoupon'));
                                    // Fast.api.open('customer/customer/edit/viewonly/1/ids/' + row.ctm_id, __('Customer info'));
                                }
                            },
                            formatter: function(value, row, index) {
                                console.log(row);
                                return (row['balance_type'] == '购券') ? '<a href="javascript:;" class="btn btn-xs btn-success btn-return-coupon" title="退优惠券"><i class="fa fa-trash"></i></a>' : '--';
                            }
                        },*/
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        {
                            field: 'balance_id',
                            title: __('Receipt Print'),
                            table: table,
                            events: {},
                            formatter: function(value, row, index) {
                                var redirectUrl = "yjyshell://" + baseUrl + "/cash/balance/generateReceipt2/balanceIds/" + value + "?aId=" + ReceiptAId;
                                var btnStr = '<a class="btn btn-xs btn-success btn-receipt" title="receipt" href="' + redirectUrl + '"><i class="fa fa-print"></i></a>';
                                return btnStr;
                            }
                        }, {
                            field: 'balance_id',
                            title: __('Invoice Print'),
                            table: table,
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        width: '110px'
                                    }
                                };
                            },
                            formatter: function(value, row, index) {
                                var btnStr = '<div class="input-group"><input type="text" class="form-control btn-modifyinvoice" readonly data-balance-id="' + value + '" id="t-invoice-' + value + '" value="' + row.invoice_no + '" style="margin: 0 auto; text-align: center" />';
                                var redirectUrl = "yjyshell://" + baseUrl + "/cash/balance/generateinvoice/balanceIds/" + value + "?aId=" + ReceiptAId;
                                btnStr += '<span class="input-group-btn"><a class="btn btn-success btn-receipt" title="receipt" href="' + redirectUrl + '" onclick="$(\'#t-invoice-' + value + '\').trigger(\'click\')"><i class="fa fa-print"></i></a></span></div>';
                                return btnStr;
                            },
                        }
                    ]
                ],
                onLoadSuccess: function(data) {
                    if (data.summary) {
                        for (var i in data.summary) {
                            $('#b_' + i).text(data.summary[i]);
                        }
                    }
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    $('.btn-modifyinvoice').each(function() {
                        $(this).on('click', function(e) {
                            var balanceId = $(this).data('balanceId');
                            var oldValue = $(this).val();//$('#t-invoice-' + balanceId).text();
                            layer.prompt({
                                formType: 0,
                                value: oldValue,
                                title: '填写发票信息',
                            }, function(value, index, elem) {
                                if (/^\d{8}$/.test(value) == false
                                    ) {
                                    layer.msg('请输入正确的发票号码', {
                                        icon: 2
                                    });
                                    return false;
                                }
                                $.ajax({
                                    url: 'cash/balance/modifyInvoice',
                                    method: 'POST',
                                    data: {
                                        balance_id: balanceId,
                                        invoice_no: value
                                    },
                                    dataType: 'json',
                                    success: function(res) {
                                        if (res.code) {
                                            $('#t-invoice-' + balanceId).val(value);
                                            layer.msg(res.msg ? __(res.msg) : '操作成功', {
                                                icon: 1
                                            });
                                        } else {
                                            layer.msg(res.msg ? __(res.msg) : '操作失败', {
                                                icon: 2
                                            });
                                        }
                                        layer.close(index);
                                    },
                                    error: function() {
                                        layer.msg('系统故障', {
                                            icon: 2
                                        });
                                        layer.close(index);
                                    }
                                });
                            });
                        })
                    })
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
            $('#btn-prestore').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/balance/prestore' + '&field=customer_id&title=' + __('Prestore');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-buy-coupon').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/balance/buycoupon' + '&field=customer_id&title=' + __('Buy coupon');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-return-coupon').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/balance/returncoupon' + '&field=customer_id&title=' + __('Return coupon');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-refund').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/balance/refund' + '&field=customer_id&title=' + __('Refund');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            // $('#btn-new-order').on('click', function() {
            //     var params = '?mode=redirect&url=' + 'cash/order/createproductorder' + '&field=customer_id&title=' + __('Create order');
            //     Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            // });
            $('#btn-new-order').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/order/createproductorder' + '&field=customer_id&title=' + __('Create order');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-adjust').on('click', function() {
                Fast.api.open('cash/balance/adjustbalance', __('Balance adjust'));
            });
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
            Form.events.datetimepicker($('.form-commonsearch'));
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'balance.balance_id': '=',
                    'balance.balance_type': '=',
                    'balance.admin_id': '=',
                    'balance.rec_admin_id': '=',
                    'balance.admin_id': '=',
                    'admin.id': '=',
                    'admin.dept_id': '=',
                    'customer.ctm_id': '=',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_name': 'LIKE %...%',
                    'balance.createtime': 'BETWEEN',
                    'balance.refund_type': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_source': '=',
                    'customer.ctm_explore': '=',
                });
            });
            $('#btn-export').on('click', function() {
                var url = '/cash/balance/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
            $('#btn-preview-todaysummary').on('click', function() {
                Fast.api.open('/cash/balance/todaysummary', '项目汇总');
            });
            $('#btn-view-dailyreport').on('click', function() {
                Fast.api.open('/cash/balance/dailyreport', '今日报表');
            });

            //变动记录
            $('#btn-view-change').on('click', function() {
                Fast.api.open('customer/accountlog/index', '变动记录');
            });
            //积分兑换
            $('#btn-exchange').on('click', function() {
                var params = '?mode=redirect&url=' + 'customer/accountlog/exchange' + '&field=customer_id&title=' + '积分兑换';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
             //定金/佣金/等级积分 调整
            $('#btn-account-adjust').on('click', function() {
                var params = '?mode=redirect&url=' + 'customer/accountlog/adjust' + '&field=customer_id&title=' + '帐户调整';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
        },
        todaysummary: function() {
            $('#btn-print-todaysummary').on('click', function() {
                $('#btn-print-todaysummary').hide();
                var hkey_root, hkey_path, hkey_key;
                if (window.navigator.userAgent.indexOf("MSIE") >= 0) {
                    hkey_root = "HKEY_CURRENT_USER";   
                    hkey_path = "\\Software\\Microsoft\\Internet Explorer\\PageSetup\\";   
                    try {       
                        var RegWsh = new ActiveXObject("WScript.Shell");       
                        hkey_key = "header";       
                        RegWsh.RegWrite(hkey_root + hkey_path + hkey_key, "");       
                        hkey_key = "footer";       
                        RegWsh.RegWrite(hkey_root + hkey_path + hkey_key, "");   
                    } catch (e) {}
                }
                window.print();
                $('#btn-print-todaysummary').show();
            })
        },
        dailyreport: function() {
            let form = $('form[role="form"]');
            Form.events.datetimepicker(form);
            $('[name="selectedDate"]').on('blur', function() {
                layer.load('提交中...');
                form.submit();
            });

            $('#btn-print-dailyreport').on('click', function() {
                $('#btn-print-dailyreport').hide();
                var hkey_root, hkey_path, hkey_key;
                if (window.navigator.userAgent.indexOf("MSIE") >= 0) {
                    hkey_root = "HKEY_CURRENT_USER";   
                    hkey_path = "\\Software\\Microsoft\\Internet Explorer\\PageSetup\\";   
                    try {       
                        var RegWsh = new ActiveXObject("WScript.Shell");       
                        hkey_key = "header";       
                        RegWsh.RegWrite(hkey_root + hkey_path + hkey_key, "");       
                        hkey_key = "footer";       
                        RegWsh.RegWrite(hkey_root + hkey_path + hkey_key, "");   
                    } catch (e) {}
                }
                window.print();
                $('#btn-print-dailyreport').show();
            })
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        prestore: function() {
            $('.payTypeDiv input').each(function() {
                $(this).on('change', function() {
                    //recal
                    let total = 0;
                    $('.payTypeDiv input').each(function() {
                        total += parseFloat($(this).val());
                    });
                    $('#coupon-cal-base').val(total);
                });
            })

            $('#btn-coupon-cal').on('click', function() {
                let couponMode = $('#coupon-cal-mode').val();
                let couponBase = $('#coupon-cal-base').val();
                let couponStep = $('#coupon-cal-step').val();
                let couponBonus = $('#coupon-cal-bonus').val();

                let couponFinalBonus = 0;
                if (couponStep > 0) {
                    if (couponMode == 1) {
                        let couponStepCnt = parseInt(couponBase / couponStep);
                        couponFinalBonus = parseFloat(couponStepCnt *  couponBonus).toFixed(2);
                    } else {
                        if (couponBase - couponStep >= 0) {
                            couponFinalBonus = parseFloat(couponBonus).toFixed(2);
                        }
                    }
                }
                $('#input-coupon-amt').val(couponFinalBonus);                
            });

            Controller.api.bindevent();
        },

        refund: function() {
            Controller.api.bindevent();
        },
        adjustbalance: function() {
            Controller.api.bindevent();
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('cash/balance/downloadprocess');
        },
        payorder: function() {
            var orderTotal = parseFloat($('#h-order-total').val());

            function reCalcPayTotal() {
                var depositeTotal = parseFloat($('[name="row[deposit_total]"]').val());
                var couponAmount = $('#input-coupon-total').val();
                $('#need-pay-total').val(parseFloat(orderTotal - depositeTotal - couponAmount).toFixed(2));
            }
            $('#field_name').on('click', function() {
                var params = '?mode=single&useFilter=1&customer_id=' + $('[name="row[customer_id]"]').val();
                Fast.api.open('customer/couponrecord/comselectpop' + params, __('Select coupon'));
            });
            $('#input-coupon-total').on('change', function() {
                reCalcPayTotal();
            });
            $('[name="row[deposit_total]"]').on('change', function() {
                reCalcPayTotal();
            });
            $('.btn-delcoupon').on('click', function() {
                $('#field_id').val('');
                $('#field_name').val('');
                $('#field_amount').val(0);
                reCalcPayTotal();
            });
            $('#btn-adjust-coupon').on('change', function() {
                $('#btn-adjust-coupon-text').text($('#btn-adjust-coupon').val());
                let useCouponTotal = parseFloat(orderTotal * parseInt($('#btn-adjust-coupon').val()) / 100).toFixed(2);
                $('#input-coupon-total').val(useCouponTotal);
                $('#input-coupon-total').trigger('change');
            })
            $('.btn-adcoupon').each(function() {
                $(this).on('click', function() {
                    $('#btn-adjust-coupon').val($(this).data('percent'));
                    $('#btn-adjust-coupon').trigger('change');
                })
            })

            Controller.api.bindevent();
        },
        buycoupon: function() {
            $('#field_name').on('click', function() {
                var params = '?mode=single&customer_id=' + $('[name="row[customer_id]"]').val();
                Fast.api.open('base/coupon/comselectpop' + params, __('Select coupon'));
            });
            $('#field_pay_amount').on('change', function() {
                var total = parseFloat($('#field_pay_amount').val());
                var depositeTotal = parseFloat($('[name="row[deposit_total]"]').val());
                $('#need-pay-total').val(total - depositeTotal);
            });
            $('[name="row[deposit_total]"]').on('change', function() {
                var total = parseFloat($('#field_pay_amount').val());
                var depositeTotal = parseFloat($('[name="row[deposit_total]"]').val());
                $('#need-pay-total').val(total - depositeTotal);
            });
            $('.btn-delcoupon').on('click', function() {
                $('#field_id').val('');
                $('#field_name').val('');
                $('#field_pay_amount').val(0);
                $('[name="row[deposit_total]"]').val(0);
                $('#need-pay-total').val(0);
            });
            Controller.api.bindevent();
        },
/*        returncoupon: function() {
            //定金与退款
            $("#select").on('change', function(){
                var val=$('#select').val();
                if(val == 1){
                    $(".returncoupon").removeClass('hidden');
                }else{
                    $(".returncoupon").addClass('hidden');
                }

            });
            
            Controller.api.bindevent();
        },*/
        chargeback: function() {
            $("[data-toggle='tooltip']").tooltip();
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