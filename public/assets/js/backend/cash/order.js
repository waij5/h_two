define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'bootstrap-select', 'selectpage', 'toastr'], function($, undefined, Backend, Table, Form, bootstrapSelect, selectpage, Toast) {
    var Controller = {
        index: function() {
            var table = initList('cash/order');
            // 为表格绑定事件
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            $("[data-toggle='tooltip']").tooltip();
            $(".btn-deduct").on('click', function() {
                var ids = $(this).data('pk');
                Fast.api.open('cash/order/deduct/ids/' + ids, __('Deduct'));
            });
            $(".btn-deduct-history").on('click', function() {
                var ids = $(this).data('pk');
                Fast.api.open('deduct/records/index/order_item_id/' + ids, __('Deduct history') + '(Item ID: ' + ids + ')');
            });
            $('.layer-footer button').on('click', function() {
                var id = $(this).attr('id');
                if (typeof id == 'undefined' || id == '') {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                } else {
                    var action = id.replace('btn-', '').replace('-', '');
                    if (action == 'chargeback') {
                        var redirectUrl = 'cash/balance/chargeback/order_id/' + $('[name=order_id]').val();
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                        parent.window.Fast.api.open(redirectUrl, __('Chargeback'));
                    } else {
                        if (action == 'payorder') {
                            // 18?dialog=1
                            var redirectUrl = 'cash/balance/payorder/ids/' + $('[name=order_id]').val();
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                            parent.window.Fast.api.open(redirectUrl, __('Pay order'));
                        } else {
                            $('#edit-order-form [name=act]').val(action);
                            $('#edit-order-form').submit();
                        }
                    }
                }
            })
            $('#btn-delivery').on('click', function() {
                var undeliveriedUrl = 'deduct/records/undeliveriedlist';
                var ids = $(this).data('pk');
                Fast.api.open(undeliveriedUrl + (undeliveriedUrl.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ids, __('Undeliveried list(Order id: %s)', ids));
            });
            $('.btn-cancel-switch').on('click', function() {
                var ids = $(this).data('pk');
                Fast.api.open('/cash/order/switchitem/ids/' + ids, "取消/转换");
            })
            $('#btn-edit-refresh').on('click', function() {
                window.location.reload();
            });
            Controller.api.bindevent();
        },
        switchitem: function() {
            var type = $('#h-order-type').val();
            $('#btn-add-product-set').parent().addClass('hidden');
            $('#btn-add-medicine-set').parent().addClass('hidden');
            $('#btn-add-project-set').parent().addClass('hidden');
            Controller.api.bindOrderEvent(type);
        },
        orderitemdetail: function() {
            var url  = 'cash/order/index';
            var op = {'balance_id': '='};
            var filter = {'balance_id': $('#h-balance_id').val()};
            url = url + '?op=' + JSON.stringify(op) + '&filter=' + JSON.stringify(filter);
            
            Table.api.init({
            });
            var table = $('#orderHistory-table');
            // 初始化表格
            table.bootstrapTable({
                url: url,
                pk: 'item_id',
                sortName: 'item_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                // height: ($(window).height() - 140),
                onLoadSuccess: function(data) {
                    console.log(data);
                    if (data.summary) {
                        var summary = data.summary;
                        $('.his-item_pay_total').text(summary.item_pay_total ? summary.item_pay_total : 0);
                        $('.his-item_coupon_total').text(summary.item_coupon_total ? summary.item_coupon_total : 0);
                        $('.his-item_total').text(summary.item_total ? summary.item_total : 0);
                    }
                },
                columns: [
                    [{
                        field: 'item_id',
                        title: __('Order_id')
                    }, {
                        field: 'item_type',
                        title: __('Order_type'),
                        formatter: function(value, row, index) {
                            return __('order_type_' + value);
                        }
                    }, {
                        field: 'ctm_name',
                        title: __('Ctm_name')
                    }, {
                        field: 'customer_id',
                        title: __('Ctm_id')
                    }, {
                        field: 'pro_name',
                        title: __('pro_name'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            }
                        },
                    }, {
                        field: 'pro_spec',
                        title: __('pro_spec'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    }, {
                        field: 'item_status',
                        title: __('Order_status'),
                        formatter: function(value, row) {
                            var sIndex = parseInt(value);
                            if (sIndex < 0) {
                                sIndex = 'm_' + Math.abs(sIndex);
                            }
                            return __('order_status_' + sIndex);
                        }
                    }, {
                        field: 'item_used_times',
                        title: __('item_used_times'),
                    }, {
                        field: 'item_total_times',
                        title: '数量',
                    }, {
                        field: 'item_ori_total',
                        title: __('item_ori_total'),
                    }, {
                        field: 'item_total',
                        title: __('item_total'),
                    }, {
                        field: 'item_pay_total',
                        title: __('item_pay_total'),
                    }, {
                        field: 'item_coupon_total',
                        title: __('item_coupon_total'),
                    }, {
                        field: 'item_original_pay_total',
                        title: __('item_original_pay_total')
                    }, {
                        field: 'consult_admin_name',
                        title: __('consult_admin_id'),
                        formatter: function(value, row, index) {
                            if (row.consult_admin_id > 0) {
                                return value;
                            } else {
                                return '<span class="text-success">-' + __('Natural diagnosis') + '-</span>';
                            }
                        },
                    }, {
                        field: 'osconsult_admin_name',
                        title: __('osconsult_admin_name'),
                    }, {
                        field: 'recept_admin_name',
                        title: __('recept_admin_name'),
                    }, {
                        field: 'prescriber_name',
                        title: __('prescriber_name'),
                    }, {
                        field: 'item_createtime',
                        title: __('Createtime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'item_paytime',
                        title: '付款时间',
                        formatter: Backend.api.formatter.datetime
                    }, {
                        field: 'item_remark',
                        title: __('item_remark'),
                        formatter: function(value, row, index) {
                            value = value ? value : '';
                            var str = '<div contenteditable="false" class="modifyContent">' + value + '</div>';
                            return str;
                        },
                    }]
                ]
            });
            Table.api.bindevent(table);
        },
        createprojectorder: function() {
            var type = 9;
            Controller.api.bindOrderEvent(type);
        },
        createrecipeorder: function() {
            var type = 1;
            Controller.api.bindOrderEvent(type);
        },
        createproductorder: function() {
            var type = 2;
            Controller.api.bindOrderEvent(type);
        },
        createorder: function() {
            // Controller.api.bindOrderEvent();
        },
        itemremark: function() {
            Controller.api.bindOrderEvent();
        },
        deductlist: function() {
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
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({
                extend: {
                    index_url: 'cash/order/deductlist',
                    edit_url: 'cash/order/deduct',
                    view_url: 'cash/order/deductview',
                    // del_url: 'cash/order/del',
                    //multi_url: 'cash/order/multi',
                    multideduct_url: 'cash/order/multideduct',
                    table: 'deductlist',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'item_id',
                sortName: 'item_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'item_type',
                        title: __('Order_type'),
                        formatter: function(value, row, index) {
                            if (value != '') {
                                return __('Order_type_' + value);
                            }
                        }
                    }, {
                        field: 'customer_id',
                        title: __('ctm_id')
                    }, {
                        field: 'ctm_name',
                        title: __('Ctm_name')
                    }, {
                        field: 'dept_name',
                        title: __('deduct dept')
                    }, {
                        field: 'pro_name',
                        title: __('TProName'),
                        formatter: Backend.api.formatter.content,
                    }, {
                        field: 'item_used_times',
                        title: __('Deduct times'),
                        formatter: function(value, row, index) {
                            return row.item_used_times + ' / ' + row.item_total_times;
                        }
                    }, {
                        field: 'item_undeducted_total',
                        title: __('non deducted amount'),
                        // formatter: function(value, row, index) {
                        //     var times = parseInt(row.item_total_times) - parseInt(row.item_used_times);
                        //     return (times * parseFloat(row.item_amount_per_time)).toFixed(2);
                        // }
                    }, {
                        field: 'pro_spec',
                        title: __('Pro_spec'),
                    }, {
                        field: 'item_paytime',
                        title: __('item_paytime'),
                        formatter: Table.api.formatter.datetime,
                    }, {
                        field: 'operate',
                        title: __('Operate'),
                        table: table,
                        events: {
                            'click .btn-viewsone': function(e, value, row, index) {
                                Fast.api.open('cash/order/deductview/ids/' + row['customer_id'], '顾客信息');
                            },
                            'click .btn-editone': Table.api.events.operate['click .btn-editone'],
                        },
                        formatter: function(value, row, index) {
                            var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-default btn-viewsone" title="用户信息" data-id=' + row['customer_id'] + '><i class="fa fa-user"></i></a>';
                            operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="划扣"><i class="fa fa-check"></i></a>';
                            return operateHtml;
                        }
                    }, ]
                ],
                onLoadSuccess: function(data) {
                    $("#table [data-toggle='tooltip']").tooltip();
                    if (typeof data.summary != 'undefined') {
                        $('#sum_unded_total').text(data.summary.undeducted_total);
                    }
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentOp = params.query.op;
                        currentFilter = params.query.filter;
                    }
                },
            });
            Table.api.bindevent(table);
            Controller.api.bindevent();
            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'order_items.customer_id': '=',
                    'order_items.dept_id': '=',
                    'order_items.item_used_times': 'BETWEEN',
                    'customer.ctm_id': '=',
                    'customer.old_ctm_code': '=',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_name': 'LIKE %...%',
                    'order_items.item_paytime': 'BETWEEN',
                    'order_items.pro_name': 'LIKE %...%',
                });
            });
            $('#toolbar').on("click", ".btn-multideduct", function(e) {
                e.preventDefault();
                e.stopPropagation();
                var customerId = 0;
                var itemType = '';
                var itemIdArr = [];
                var selections = table.bootstrapTable('getSelections');
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    // $.map(table.bootstrapTable('getSelections'), function(row) {
                    row = selections[curIndex];
                    if (customerId == 0) {
                        customerId = row.customer_id;
                    } else {
                        if (customerId != row.customer_id) {
                            Layer.msg(__('Could not deduct items which are not belong to single customer!'), {
                                icon: 2
                            });
                            return false;
                        }
                    }
                    if (itemType == '') {
                        itemType = row.item_type;
                    } else {
                        if (itemType != row.item_type) {
                            Layer.msg(__('Could not deduct items of different types!'), {
                                icon: 2
                            });
                            return false;
                        }
                    }
                    itemIdArr.push(row.item_id);
                };
                if (itemIdArr.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                } else {
                    var itemIdParam = itemIdArr.join(',');
                    var options = table.bootstrapTable('getOptions');
                    Fast.api.open(options.extend.multideduct_url + (options.extend.multideduct_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + itemIdParam + '&customerId=' + customerId + '&itemType=' + itemType, __('batch operate'));
                }
            });
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            });
            //导出
            $('#btn-export').on('click', function() {
                var url = '/cash/order/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('/cash/order/downloadprocess');
        },
        deductview: function() {
            if ($('.layui-layer-footer').length != 0) {
                $('.iframeFoot').remove();
            }
            Backend.initConsultHistory('#conHistory-table', '#conHistory-ids', Table);
            Backend.initOsconsultHistory('#osconHistory-table', '#osconHistory-ids', Table);
            Backend.initRvinfoHistory('#rvinfoHistory-table', '#rvinfoHistory-ids', Table);
            Backend.initOrderItemsHistory('#orderHistory-table', '#orderHistory-ids', Table, true, "deduct");
            Backend.initHMHistory('#hmOrderHistory-table', '#orderHistory-ids', Table, true);
            $('#btn-refresh-rvinfo').on('click', function() {
                $('#rvinfoHistory-table').bootstrapTable('refresh');
            });
            $('#btn-refresh-order').on('click', function() {
                $('#orderHistory-table').bootstrapTable('refresh');
            });
            //首次受理工具更改申请
            $('#firstToolId').on('click', function() {
                var ctm_id = $(this).attr('value');
                $.ajax({
                    url: 'customer/customer/firstToolIdApply',
                    data: {
                        customerId: ctm_id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.code) {
                            Toastr.success(__('Operation completed'));
                        } else {
                            Toastr.error('客户没有网电客服记录,请添加');
                        }
                    }
                })
            });
            //废弃客户申请
            $('#ctmStatus').on('click', function() {
                var ctm_id = $(this).attr('value');
                layer.confirm(__('Is Discarded?'), function(index, layero) {
                    $.ajax({
                        url: 'customer/customer/invalidCustomer',
                        data: {
                            customerId: ctm_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.code) {
                                msg = res.msg ? res.msg : __('Operation completed');
                                layer.msg(msg, {
                                    icon: 1
                                });
                            } else {
                                msg = res.msg ? res.msg : __('Operation failed');
                                layer.msg(msg, {
                                    icon: 2
                                });
                            }
                        }
                    })
                })
            });
            //添加回访计划
            document.getElementById("add_rvtype").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                // var fat_id = $('select[name="row[fat_id]"]').val();
                // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
            };
            document.getElementById("addRvinfoHistory").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                Fast.api.open("customer/rvinfo/add?ctm_id=" + ctm_id, __('Add'));
            };
            var customerId = $('[name="row[ctm_id]"]').val();
            $('#add_rvinfo_by_plan').on('click', function() {
                $.ajax({
                    url: 'customer/rvinfo/addplaninfos',
                    data: {
                        planId: $('#h_rvinfo_by_plan').val(),
                        customerId: customerId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.code) {
                            $('#rvinfoHistory-table').bootstrapTable('refresh');
                            Toastr.success(__('Operation completed'));
                        } else {
                            Toastr.error(__('Operation failed'));
                        }
                        //trigger refresh btn
                    }
                })
            })
            $('.btn-close').on('click', function() {
                parent.Layer.close(parent.Layer.getFrameIndex(window.name));
            })
            osTableHeight = $(window).height() - 204;
            $('#rvinfoHistory').find('.fixed-table-body').css('height', osTableHeight)
            Controller.api.bindevent();
            layer.photos({
                photos: '#customer-img-list',
                //shift: 5 //0-6的选择，指定弹出图片动画类型，默认随机
            });
        },
        deduct: function() {
            $('#btn-browser-file').on('click', function() {
                $('#f-imgupload').trigger('click');
            })
            $('#f-imgupload').change(function(res) {
                if ($('#f-imgupload')[0].files.length) {
                    var showTips = '共' + $('#f-imgupload')[0].files.length + '张图片： ';
                    for (var i = 0; i < $('#f-imgupload')[0].files.length; i++) {
                        showTips += $('#f-imgupload')[0].files[i].name + ' ';
                    }
                    $('#t-file-name').val(showTips);
                    $('#chksub').prop('disabled', false);
                } else {
                    $('#t-file-name').val('请上传票据');
                    $('#chksub').prop('disabled', true);
                }
            })
            Controller.api.bindAddStaff();
            Form.events.selectpicker($('#edit-order-form'));
            var fSubmit = function() {
                var form = this;
                if (form.data('yjy_is_submitting')) {
                    layer.msg('正在提交中，请稍候...');
                    return false;
                }
                form.data('yjy_is_submitting', 1);
                var type = form.attr("method").toUpperCase();
                type = type && (type === 'GET' || type === 'POST') ? type : 'GET';
                url = form.attr("action");
                url = url ? url : location.href;
                //调用Ajax请求方法
                Fast.api.ajax({
                    type: type,
                    url: url,
                    data: new FormData(form[0]),
                    cache: false,
                    processData: false,
                    contentType: false
                }, function(data, ret) {
                    form.data('yjy_is_submitting', 0);
                    $('.form-group', form).removeClass('has-feedback has-success has-error');
                    var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation completed');
                    if (!ret.hasOwnProperty('code') || ret.code == 0) {
                        parent.Toastr.error(et.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation failed'));
                        return false;
                    }

                    parent.Toastr.success(msg);
                    parent.$(".btn-refresh").trigger("click");
                    var index = parent.Layer.getFrameIndex(window.name);
                    parent.Layer.close(index);
                }, function(data, ret) {
                    form.data('yjy_is_submitting', 0);
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='__token__']", form).val(data.token);
                    }
                    if (typeof error === 'function') {
                        if (!error.call(form, data, ret)) {
                            return false;
                        }
                    }
                });
            };
            Form.events.validator($('#edit-order-form'), null, null, fSubmit);
        },
        multideduct: function() {
            $('#btn-browser-file').on('click', function() {
                $('#f-imgupload').trigger('click');
            })
            $('#f-imgupload').change(function(res) {
                if ($('#f-imgupload')[0].files.length) {
                    var showTips = '共' + $('#f-imgupload')[0].files.length + '张图片： ';
                    for (var i = 0; i < $('#f-imgupload')[0].files.length; i++) {
                        showTips += $('#f-imgupload')[0].files[i].name + ' ';
                    }
                    $('#t-file-name').val(showTips);
                    $('#chksub').prop('disabled', false);
                } else {
                    $('#t-file-name').val('请上传票据');
                    $('#chksub').prop('disabled', true);
                }
            })
            Controller.api.bindAddStaff();
            // Form.events.selectpicker($('#edit-order-form'));
            var fSubmit = function() {
                var form = this;
                if (form.data('yjy_is_submitting')) {
                    layer.msg('正在提交中，请稍候...');
                    return false;
                }
                form.data('yjy_is_submitting', 1);
                var type = form.attr("method").toUpperCase();
                type = type && (type === 'GET' || type === 'POST') ? type : 'GET';
                url = form.attr("action");
                url = url ? url : location.href;
                //调用Ajax请求方法
                Fast.api.ajax({
                    type: type,
                    url: url,
                    data: new FormData(form[0]),
                    cache: false,
                    processData: false,
                    contentType: false
                }, function(data, ret) {
                    form.data('yjy_is_submitting', 0);
                    $('.form-group', form).removeClass('has-feedback has-success has-error');
                    var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation completed');
                    if (!ret.hasOwnProperty('code') || ret.code == 0) {
                        parent.Toastr.error(et.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation failed'));
                        return false;
                    }

                    parent.Toastr.success(msg);
                    parent.$(".btn-refresh").trigger("click");
                    var index = parent.Layer.getFrameIndex(window.name);
                    parent.Layer.close(index);
                }, function(data, ret) {
                    form.data('yjy_is_submitting', 0);
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='__token__']", form).val(data.token);
                    }
                    if (typeof error === 'function') {
                        if (!error.call(form, data, ret)) {
                            return false;
                        }
                    }
                });
            };

            // Form.events.selectpicker($('#edit-order-form'));
            // Form.api.bindevent($("#edit-order-form"));
            var form = $('#edit-order-form');
            if ($(".y-selectpicker").size() > 0) {
                require(['bootstrap-select', 'bootstrap-select-lang'], function() {
                    $('.y-selectpicker').each(function() {
                        let tId = $(this).attr('id');
                        $('#' + tId).selectpicker();
                    });
                });
            }
            // Form.api.bindevent($("#edit-order-form"));
            // Form.eventvalidator(form, success, error, submit);

            Form.events.validator($('#edit-order-form'), null, null, fSubmit);
        },
        changedeveloper: function() {
            Table.api.init({
                extend: {
                    index_url: 'cash/order/changedeveloper',
                    pay_url: 'cash/order/changeadmin',
                    cancel_url: 'cash/order/cancelorder',
                    table: 'order_info',
                }
            });
            var table = $("#table");
            var showCancelBtn = table.data('operate-payorder');
            var showPayBtn = table.data('operate-cancelorder');
            var showDeductBtn = table.data('operate-deduct');
            var showSwitchBtn = table.data('operate-switchitem');
            var forbiddenTrIndexes = new Array();
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'item_id',
                sortName: 'item_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                height: ($(window).height() - 140),
                onLoadSuccess: function(data) {
                    var checkboxes = table.find('.bs-checkbox>input');
                    $.each(forbiddenTrIndexes, function(key, value) {
                        if (checkboxes.eq(value)) {
                            checkboxes.eq(value).prop('disabled', true);
                        }
                    });
                    forbiddenTrIndexes = new Array();
                },
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'item_id',
                        title: __('Order_id')
                    }, {
                        field: 'item_type',
                        title: __('Order_type'),
                        formatter: function(value, row, index) {
                            return __('order_type_' + value);
                        }
                    }, {
                        field: 'ctm_name',
                        title: __('Ctm_name')
                    }, {
                        field: 'customer_id',
                        title: __('Ctm_id')
                    }, {
                        field: 'pro_name',
                        title: __('pro_name'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            }
                        },
                    }, {
                        field: 'pro_spec',
                        title: __('pro_spec'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    }, {
                        field: 'item_status',
                        title: __('Order_status'),
                        formatter: function(value, row) {
                            var sIndex = parseInt(value);
                            if (sIndex < 0) {
                                sIndex = 'm_' + Math.abs(sIndex);
                            }
                            return __('order_status_' + sIndex);
                        }
                    }, {
                        field: 'consult_admin_name',
                        title: '开单营销人员',
                        formatter: function(value, row, index) {
                            if (row.consult_admin_id > 0) {
                                return value;
                            } else {
                                return '<span class="text-success">-' + __('Natural diagnosis') + '-</span>';
                            }
                        },
                    }, {
                        field: 'osconsult_admin_name',
                        title: __('osconsult_admin_name'),
                    }, {
                        field: 'recept_admin_name',
                        title: __('recept_admin_name'),
                    }, {
                        field: 'prescriber_name',
                        title: __('prescriber_name'),
                    }, {
                        field: 'item_createtime',
                        title: __('Createtime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'item_paytime',
                        title: '付款时间',
                        formatter: Backend.api.formatter.datetime
                    }, ]
                ]
            });
            Table.api.bindevent(table);
            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'order_items.item_id': '=',
                    'order_items.item_status': '=',
                    'order_items.item_type': '=',
                    'customer.ctm_id': '=',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_name': 'LIKE %...%',
                    'order_items.admin_id': '=',
                    'order_items.consult_admin_id': '=',
                    'order_items.item_createtime': 'BETWEEN',
                });
            });
            $('#a-search-customer').on('click', function() {
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            //datepicker
            Form.events.datetimepicker($("form[role=form]"));
            $('#btn-new-order').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/order/createorder' + '&field=customer_id&title=' + __('Create order');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
            $('.btn-change-developer').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var customerId = 0;
                var itemIdArr = new Array();
                var selections = table.bootstrapTable('getSelections');
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    // $.map(table.bootstrapTable('getSelections'), function(row) {
                    row = selections[curIndex];
                    if (customerId == 0) {
                        customerId = row.customer_id;
                    } else {
                        if (customerId != row.customer_id) {
                            Layer.msg(__('Could not take different customer!'), {
                                icon: 2
                            });
                            return false;
                        }
                    }
                    itemIdArr.push(row.item_id);
                };
                if (itemIdArr.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                } else {
                    var itemIdParam = itemIdArr.join(',');
                    var options = table.bootstrapTable('getOptions');
                    Fast.api.open(options.extend.pay_url + (options.extend.pay_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + itemIdParam + '&customerId=' + customerId, __('Pay order'));
                }
            });
            return table;
        },
        changeadmin: function() {
            Form.events.selectpicker($("form[role=form]"));
            $('#btn-submit').on('click', function() {
                $('#btn-submit').prop('disabled', true);
                $.post({
                    url: '/cash/order/changeadmin',
                    data: {
                        adminid: $('[name=adminid]').val(),
                        id: $('[name=id]').val(),
                    },
                    success: ret => {
                        if (ret.code > 0) {
                            var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation completed');
                            parent.Toastr.success(msg);
                            parent.$(".btn-refresh").trigger("click");
                            var index = parent.Layer.getFrameIndex(window.name);
                            parent.Layer.close(index);
                            return false;
                        } else {
                            var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation failed');
                            Toastr.error(msg);
                            return false;
                        }
                    },
                    complete: function() {
                        $('#btn-submit').prop('disabled', false);
                    }
                })
            })
            return false;
        },
        payedlist: function() {
            var table = initList('cash/order/payedlist')
        },
        cancellist: function() {
            var table = initList('cash/order/cancellist');
        },
        pendinglist: function() {
            var table = initList('cash/order/pendinglist');
        },
        completedlist: function() {
            var table = initList('cash/order/completedlist');
        },
        operation: function() {
            var table = initList('cash/order/operation');
        },
        deptconsumption: function() {
            var table = initList('cash/order/deptconsumption');
        },
        productorderlist: function() {
            var table = initList('cash/order/productorderlist');
        },
        adminfilteredlist: function() {
            var table = initList('cash/order/adminfilteredlist');
        },
        api: {
            bindevent: function(fnSuccess) {
                if (typeof fnSuccess != undefined) {
                    Form.api.bindevent($("form[role=form]", fnSuccess));
                } else {
                    Form.api.bindevent($("form[role=form]"));
                }
            },
            bindSelPro: function(proType) {
                $('#selector-pro').selectPage({
                    data: '/base/project/comselectpop',
                    params: function() {
                        return {
                            "pkey_name": "pro_id",
                            "order_by": [
                                ["pro_id", "ASC"],
                            ],
                            "field": "pro_name",
                            "yjyCustom[pro_type]": proType,
                            "yjyCustom[pro_status]": 1,
                        };
                    },
                    pageSize: 10,
                    showField: "pro_name",
                    searchField: "pro_name,pro_spec,pro_spell",
                    keyField: 'pro_id',
                    andOr: "OR",
                    multiple: false,
                    pagination: true,
                    showField: "pro_name",
                    eAjaxSuccess: function(data) {
                        return data;
                    },
                    formatItem: function(data) {
                        return data.pro_amount + " | " + data.pro_name + " | " + data.pro_spec;
                    },
                    eSelect: function(data) {
                        comselcallback(data);
                    },
                });
                if ($('.sp_container')) {
                    var selectWidth = $('.sp_container').width();
                    $('.sp_result_area').css('width', selectWidth);
                }
            },
            events: {
                operate: {
                    'click .btn-payone': function(e, value, row, index) {
                        e.stopPropagation();
                        var options = $(this).closest('table').bootstrapTable('getOptions');
                        Fast.api.open(options.extend.pay_url + (options.extend.pay_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] + '&customerId=' + row['customer_id'], __('Pay order'));
                    },
                    'click .btn-deduct': function(e, value, row, index) {
                        e.stopPropagation();
                        Fast.api.open('cash/order/deduct/ids/' + row.item_id, __('Deduct') + '(Item ID: ' + row.item_id + ')');
                    },
                    // 备注
                    'click .btn-item_remark': function(e, value, row, index) {
                        e.stopPropagation();
                        Fast.api.open('cash/order/itemremark/ids/' + row.item_id, __('ItemRemark') + '(Item ID: ' + row.item_id + ')');
                    },
                    'click .btn-deduct-history': function(e, value, row, index) {
                        e.stopPropagation();
                        Fast.api.open('deduct/records/index/order_item_id/' + row.item_id, __('Deduct history') + '(Item ID: ' + row.item_id + ')');
                    },
                    'click .btn-cancel-switch': function(e, value, row, index) {
                        e.stopPropagation();
                        Fast.api.open('/cash/order/switchitem/ids/' + row.item_id, "取消/转换");
                    },
                    'click .btn-cancel': function(e, value, row, index) {
                        e.stopPropagation();
                        var index = Layer.confirm(__('Are you sure you want to cancel this item?'), {
                            icon: 3,
                            title: __('Warning'),
                            shadeClose: true
                        }, function(index, layero) {
                            layer.load();
                            $.ajax({
                                url: 'cash/order/cancelorder',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    ids: row['item_id'],
                                },
                                success: function(data) {
                                    layer.closeAll('loading');
                                    layer.msg(__('Operation completed'), {
                                        icon: 1
                                    });
                                    window.location.reload();
                                },
                                error: function() {
                                    layer.closeAll('loading');
                                    layer.msg(__('Error occurs'), {
                                        icon: 2
                                    });
                                },
                            });
                        });
                    }
                }
            },
            bindAddStaff: function() {
                //输入关键字查找职员
                $('.btn-addstaff').on('click', function() {
                    //stafflist ele id rule: staff-roleId-staffId
                    var roleId = $(this).data('role-id');
                    var selectedIndex = $('#sel-staff-' + roleId)[0].selectedIndex;
                    var selectedStaffEle = $('#sel-staff-' + roleId + ' option').eq(selectedIndex);
                    var selectedStaffId = selectedStaffEle.val();
                    var selectedStaffName = selectedStaffEle.text();
                    if (selectedStaffId) {
                        var inputStaffId = 'staff_' + roleId + '_' + selectedStaffId;
                        if ($('#' + inputStaffId).length == 0) {
                            var inputStaffNamePre = 'deduct[' + roleId + '][' + selectedStaffId + ']';
                            var inputStaffIdName = inputStaffNamePre + '[admin_id]';
                            var inputStaffPercentName = inputStaffNamePre + '[percent]';
                            var trKey = 'tr-' + inputStaffId;
                            var rowHtml = '<tr id="' + trKey + '">' + '<td class="text-center" style="vertical-align:middle">' + '<input type="hidden" name="' + inputStaffIdName + '" id="' + inputStaffId + '" value="' + selectedStaffId + '" />' + selectedStaffName + '</td>' + '<td style="padding:0; width: 60px;"><input class="form-control" type="number" name="' + inputStaffPercentName + '" value="100" data-rule="required;integer;range[1-100]" style="border:none"/></td>' + '<td style="vertical-align:middle;text-align:center; width: 40px;"><a href="javascript:;" data-tr-key="' + trKey + '" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a></td>' + '</tr>';
                            $('#t-role-' + roleId).append(rowHtml);
                        }
                    } else {
                        Toastr.error('staff can not be empty');
                    }
                });
                $('#table').on('click', '.btn-delone', function(e) {
                    e.stopPropagation;
                    var trId = $(e.currentTarget).data('tr-key');
                    $('#' + trId).remove();
                })
            },
            bindOrderEvent: function(type) {
                var params = '?mode=cusmulti&url=' + 'cash/order/createorder&pkinputname=pro_id[]&title=' + __('Create order');
                var setParams = '?mode=single';
                $('#btn-add-project-set').on('click', function() {
                    Fast.api.open('base/prosets/comselectpop' + setParams + '&setType=9&fldSelPre=@project_', __('Select project'));
                });
                $('#btn-add-medicine-set').on('click', function() {
                    Fast.api.open('base/prosets/comselectpop' + setParams + '&setType=1&fldSelPre=@product_1_', __('Select project'));
                });
                $('#btn-add-product-set').on('click', function() {
                    Fast.api.open('base/prosets/comselectpop' + setParams + '&setType=2&fldSelPre=@product_2_', __('Select project'));
                });
                //set-id,set-name change都会触发，但由于set-name是后触发的直接监听set-name的change
                $('.h-set-name').on('change', function() {
                    var setId = $(this).prev('.h-set-id').val();
                    var setName = $(this).val();
                    var loadIndex = layer.load();
                    $.post({
                        url: 'base/prosets/render/setId/' + setId,
                        dataType: 'json',
                        success: function(data) {
                            if (typeof data.code != 'undefined' && !data.code) {
                                layer.msg(__(data.msg), {
                                    icon: 2
                                })
                                return false;
                            }
                            for (var i in data) {
                                window.comselcallback(data[i]);
                            }
                            Layer.close(loadIndex);
                        },
                        error: function() {
                            Layer.close(loadIndex);
                            Toastr.error('Error occurs');
                        }
                    });
                })
                //绑定 项目选择
                Controller.api.bindSelPro(type);
                //清空选择
                $('#btn-clear-project').on('click', function() {
                    $('#t-project-select tbody').html('');
                    $('#sum-ori-total').text(0);
                    $('#sum-total').text(0);
                    $('#sum-discount-percent').text('--');
                    proDetails = [];
                });
                $('[name="permissionRequest"]').on('click', function() {
                    if ($(this).prop('checked')) {
                        $('#h-applyInfo').removeClass('hidden');
                    } else {
                        $('#h-applyInfo').addClass('hidden');
                    }
                })
                //清理暂存数组, 删除行
                $('#t-project-select tbody').on('click', '.btn-delone', function() {
                    var selectedTr = $(this).parents('tr');
                    var selectedPk = selectedTr.data('pk');
                    if (typeof proDetails[selectedPk] != 'undefined') {
                        delete proDetails[selectedPk];
                    }
                    selectedTr.remove();
                    reCalSum();
                });
                Controller.api.bindevent();
            }
        }
    };
    var proDetails = [];
    window.comselcallback = function(row) {
        var detailId = 'project' + row.pro_id;
        var plusQty = row.row_qty ? parseInt(row.row_qty) : 1;
        if ($('#t-project-select tbody [data-pk="' + detailId + '"]').length > 0) {
            //change qty ,trigger recalc;
            var qtyEle = $('#t-project-select tbody [data-pk="' + detailId + '"]').find('.tag-row_qty');
            var qty = parseInt(qtyEle.val()) + plusQty;
            var type = getChangeType(qtyEle);
            updateRowData(type, $(qtyEle).parents('tr'), qty);
            reCalSum();
        } else {
            //初始化 相应行数据
            //项目　初始时total实际为amout * 1, * 1省略
            // proDetails[detailId] = row;
            proDetails[detailId] = {};
            proDetails[detailId]['row_qty'] = plusQty;
            proDetails[detailId]['row_ori_pk'] = row.pro_id;
            proDetails[detailId]['row_type'] = 'project';
            let proStock = '不限';
            if (row.pro_type == 9) {
                var rowTypeName = __('Type_project');
            } else {
                proStock = row.pro_stock > 999 ? '999+' : row.pro_stock;
                if (row.pro_type == 1) {
                    var rowTypeName = __('Type_product_1');
                } else {
                    var rowTypeName = __('Type_product_2');
                }
            }
            proDetails[detailId]['row_type_name'] = rowTypeName;
            proDetails[detailId]['row_name'] = row.pro_name;
            proDetails[detailId]['pro_spec'] = row.pro_spec;
            proDetails[detailId]['pro_stock'] = proStock;
            proDetails[detailId]['row_ori_amount'] = row.pro_amount;
            proDetails[detailId]['row_ori_total'] = proDetails[detailId]['row_qty'] * proDetails[detailId]['row_ori_amount'];
            proDetails[detailId]['row_pk'] = detailId;
            proDetails[detailId]['row_qty'] = plusQty;
            proDetails[detailId]['row_amount'] = (typeof row.row_amount != 'undefined') ? row.row_amount : proDetails[detailId]['row_ori_amount'];
            proDetails[detailId]['row_total'] = proDetails[detailId]['row_amount'] * proDetails[detailId]['row_qty'];
            if (proDetails[detailId]['row_ori_total'] > 0) {
                proDetails[detailId]['row_discount_percent'] = parseFloat(100.0 * proDetails[detailId]['row_amount'] / proDetails[detailId]['row_ori_amount']).toFixed(2);
            } else {
                proDetails[detailId]['row_discount_percent'] = 100.00;
            }
            var rowHtml = generateRowHtml(proDetails[detailId]);
            $('#t-project-select tbody').append(rowHtml);
            reCalSum();
            $('[data-pk="' + proDetails[detailId]['row_pk'] + '"] input').on('change', function() {
                var type = getChangeType(this);
                updateRowData(type, $(this).parents('tr'), $(this).val());
                reCalSum();
            })
        }
    }

    function generateRowHtml(row) {
        var readonlyStr = '';
        if (row.pro_amount <= 0) {
            readonlyStr = ' readonly';
        }
        var inputNamePre = 'itemParams[' + row.row_pk + ']';
        var rowHtml = '<tr data-pk="' + row.row_pk + '">' + '<td style="vertical-align:middle" class="text-center">' + '<input type="hidden"class=" form-control" name="' + inputNamePre + '[pk]" value="' + row.row_ori_pk + '" />' + row.row_type_name + '</td>' + '<input type="hidden"class=" form-control" name="' + inputNamePre + '[type]" value="' + row.row_type + '" />' + row.row_type_name + '</td>' + '<td class="col-xs-2 col-md-2 text-center" style="vertical-align:middle">' + Backend.api.formatter.content(row.row_name, "", "", 16) + '</td>' + '<td class="col-xs-2 col-md-2 text-center" style="vertical-align:middle">' + row.pro_spec + '</td>' + '<td style="vertical-align:middle" class="text-center"><lable class="control-label tag-row_stock">' + row.pro_stock + '</lable></td>' + '<td style="vertical-align:middle" class="text-center"><lable class="control-label tag-row_amount">' + row.row_ori_amount + '</lable></td>' + '<td style="width:12%" class="text-center"><input type="number" class="tag-row_qty form-control" name="' + inputNamePre + '[qty]" value="' + row.row_qty + '" data-rule="required;integer[+0]" /></td>' + '<td style="vertical-align:middle" class="text-center"><lable class="control-label tag-row_ori_total">' + row.row_ori_total + '</lable></td>' + '<td class="text-center"><input type="number" class="tag-row_total form-control"' + readonlyStr + ' name="' + inputNamePre + '[item_total]" data-rule="required;range[1+]" value="' + row.row_total + '" /></td>' + '<td style="width:14%" class="text-center"><input type="number" class="tag-row_discount_percent form-control"' + readonlyStr + ' name="' + inputNamePre + '[discount_percent]" value="' + row.row_discount_percent + '" data-rule="required;range(0~100);" /></td>' + '<td style="vertical-align:middle" class="text-center"><a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a></td>' + '</tr>';
        return rowHtml;
    }
    //单行改变
    function updateRowData(type, tr, data) {
        var trEle = $(tr);
        var pk = trEle.data('pk');
        // type
        if (type == 'row_qty') {
            if (isNaN(data) || (data = parseInt(data)) <= 0) {
                data = 1;
            }
            proDetails[pk]['row_qty'] = data;
            proDetails[pk]['row_ori_total'] = ((proDetails[pk]['row_ori_amount']) * (proDetails[pk]['row_qty'])).toFixed(2);
            proDetails[pk]['row_total'] = ((proDetails[pk]['row_amount']) * (proDetails[pk]['row_qty'])).toFixed(2);
        } else {
            if (type == 'row_total') {
                proDetails[pk]['row_total'] = parseFloat(data).toFixed(2);
                proDetails[pk]['row_amount'] = (proDetails[pk]['row_total'] / proDetails[pk]['row_qty']).toFixed(2);
                if (proDetails[pk]['row_ori_amount'] > 0) {
                    proDetails[pk]['row_discount_percent'] = (100.0 * proDetails[pk]['row_amount'] / proDetails[pk]['row_ori_amount']).toFixed(2);
                } else {
                    proDetails[pk]['row_discount_percent'] = '--';
                }
            } else {
                //改变百分比
                proDetails[pk]['row_discount_percent'] = parseFloat(data).toFixed(2);
                proDetails[pk]['row_amount'] = (proDetails[pk]['row_ori_amount'] * proDetails[pk]['row_discount_percent'] / 100).toFixed(2);
                proDetails[pk]['row_total'] = (proDetails[pk]['row_amount'] * proDetails[pk]['row_qty']).toFixed(2);
            }
        }
        $(tr).find('.tag-row_qty').val(proDetails[pk]['row_qty']);
        $(tr).find('.tag-row_ori_total').text(proDetails[pk]['row_ori_total']);
        $(tr).find('.tag-row_total').val(proDetails[pk]['row_total']);
        $(tr).find('.tag-row_discount_percent').val(proDetails[pk]['row_discount_percent']);
    }

    function getChangeType(ele) {
        var ele = $(ele);
        if (ele.hasClass('tag-row_qty')) {
            return 'row_qty';
        } else {
            if (ele.hasClass('tag-row_total')) {
                return 'row_total';
            } else {
                return 'row_discount_percent';
            }
        }
    }
    //重新统计
    function reCalSum() {
        var summary = {
            // qty: 0,
            ori_total: 0,
            total: 0,
            discount_percent: 100
        };
        for (var i in proDetails) {
            var row = proDetails[i];
            summary.ori_total += parseFloat(row['row_ori_total']);
            summary.total += parseFloat(row['row_total']);
        }
        if (summary.total > 0) {
            summary.discount_percent = (100.0 * summary.total / summary.ori_total).toFixed(2);
        } else {
            summary.discount_percent = '--';
        }
        $('#sum-ori-total').text(summary.ori_total);
        $('#sum-total').text(summary.total);
        $('#sum-discount-percent').text(summary.discount_percent);
    }

    function initList(indexUrl) {
        Form.events.datetimepicker($('.form-commonsearch'));
        Form.events.selectpicker($("form[role=form]"));
        Table.api.init({
            extend: {
                index_url: indexUrl,
                // edit_url: 'cash/order/edit',
                pay_url: 'cash/balance/payorder',
                cancel_url: 'cash/order/cancelorder',
                // del_url: 'cash/order/del',
                // multi_url: 'cash/order/multi',
                table: 'order_info',
            }
        });
        if (indexUrl == 'cash/order/deptconsumption') {
            $totalTimesStr = '数量';
        } else {
            $totalTimesStr = __('item_total_times');
        }
        var table = $("#table");
        var showCancelBtn = table.data('operate-payorder');
        var showPayBtn = table.data('operate-cancelorder');
        var showDeductBtn = table.data('operate-deduct');
        var showSwitchBtn = table.data('operate-switchitem');
        var forbiddenTrIndexes = new Array();
        // 初始化表格
        table.bootstrapTable({
            url: $.fn.bootstrapTable.defaults.extend.index_url,
            pk: 'item_id',
            sortName: 'item_id',
            sortOrder: 'DESC',
            search: false,
            commonSearch: false,
            height: ($(window).height() - 140),
            onLoadSuccess: function(data) {
                var checkboxes = table.find('.bs-checkbox>input');
                $.each(forbiddenTrIndexes, function(key, value) {
                    if (checkboxes.eq(value)) {
                        checkboxes.eq(value).prop('disabled', true);
                    }
                });
                forbiddenTrIndexes = new Array();
            },
            columns: [
                [{
                    checkbox: true
                }, {
                    field: 'item_id',
                    title: __('Order_id')
                }, {
                    field: 'item_type',
                    title: __('Order_type'),
                    formatter: function(value, row, index) {
                        return __('order_type_' + value);
                    }
                }, {
                    field: 'ctm_name',
                    title: __('Ctm_name')
                }, {
                    field: 'customer_id',
                    title: __('Ctm_id')
                }, {
                    field: 'pro_name',
                    title: __('pro_name'),
                    // formatter: function (value) {
                    //     return '<a href="javascript:;" class="btn-view-deduct"><i class="fa fa-search"></i> ' + value + '</a>';
                    // },
                    // events: {
                    //     'click .btn-view-deduct': function(e, value, row, index) {
                    //         e.stopPropagation();
                    //         orderTable.find('tbody .deepShow').removeClass('deepShow');
                    //         $(e.currentTarget).parents('tr').addClass('deepShow');
                    //         Backend.initDeductTable('#h-deducted-table', Table, row.item_id);
                    //     }
                    // },
                    cellStyle: function(value) {
                        return {
                            css: {
                                'width': '120px',
                                'min-width': '120px',
                                "word-wrap": "normal",
                                'text-align': 'left',
                            }
                        }
                    },
                }, {
                    field: 'pro_spec',
                    title: __('pro_spec'),
                    cellStyle: function(value) {
                        return {
                            css: {
                                'width': '120px',
                                'min-width': '120px',
                                "word-wrap": "normal",
                                'text-align': 'left',
                            }
                        };
                    },
                }, {
                    field: 'item_status',
                    title: __('Order_status'),
                    formatter: function(value, row) {
                        var sIndex = parseInt(value);
                        if (sIndex < 0) {
                            sIndex = 'm_' + Math.abs(sIndex);
                        }
                        return __('order_status_' + sIndex);
                    }
                }, {
                    field: 'item_used_times',
                    title: __('item_used_times'),
                }, {
                    field: 'item_total_times',
                    title: $totalTimesStr,
                }, {
                    field: 'item_ori_total',
                    title: __('item_ori_total'),
                }, {
                    field: 'item_total',
                    title: __('item_total'),
                }, {
                    field: 'item_pay_total',
                    title: __('item_pay_total'),
                }, {
                    field: 'item_coupon_total',
                    title: __('item_coupon_total'),
                }, {
                    field: 'item_original_pay_total',
                    title: __('item_original_pay_total')
                }, {
                    field: 'consult_admin_name',
                    title: __('consult_admin_id'),
                    formatter: function(value, row, index) {
                        if (row.consult_admin_id > 0) {
                            return value;
                        } else {
                            return '<span class="text-success">-' + __('Natural diagnosis') + '-</span>';
                        }
                    },
                }, {
                    field: 'osconsult_admin_name',
                    title: __('osconsult_admin_name'),
                }, {
                    field: 'recept_admin_name',
                    title: __('recept_admin_name'),
                }, {
                    field: 'prescriber_name',
                    title: __('prescriber_name'),
                }, {
                    field: 'item_createtime',
                    title: __('Createtime'),
                    formatter: Table.api.formatter.datetime
                }, {
                    field: 'item_paytime',
                    title: '付款时间',
                    formatter: Backend.api.formatter.datetime
                }, 
                {
                    field: 'item_remark',
                    title: __('item_remark'),
                    formatter: function(value, row, index) {
                        value = value ? value : '';
                        var str = '<div contenteditable="false" class="modifyContent">' + value + '</div>';
                        return str;
                    },
                }, 
                {
                    field: 'operate',
                    title: __('Operate'),
                    events: Controller.api.events.operate,
                    formatter: function(value, row, index) {
                        //edit page, logic to decide which operate is allowed
                        //pending pay, edit, cancel
                        operateHtml = '';
                        if (row.item_status == 0) {
                            //付款
                            if (showCancelBtn) {
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-success btn-payone"><i class="fa fa-dollar"></i>收款</a>';
                            }
                            //取消
                            if (showCancelBtn) {
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-cancel"><i class="fa fa-trash text-danger"></i>撤单</a>';
                            }
                        } else {
                            forbiddenTrIndexes.push(index);
                            if (row.item_status == -3) {
                                //取消
                                if (showCancelBtn) {
                                    operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-cancel"><i class="fa fa-trash text-danger"></i>撤单</a>';
                                }
                            } else if (row.item_status == 1) {
                                //划扣，退换
                                if (showDeductBtn) {
                                    operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-deduct" title="划扣"><i class="fa fa-check text-success">划扣</i></a>';
                                }
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-deduct-history" title="划扣信息"><i class="fa fa-info-circle">信息</i></a>';
                                if (showSwitchBtn) {
                                    operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-cancel-switch"><i class="fa fa-refresh text-warning">退换</i></a>';
                                }
                            } else if (row.item_status == 2) {
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-deduct-history" title="划扣信息"><i class="fa fa-info-circle">信息</i></a>';
                            }
                        }
                         operateHtml += '<a href="javascript:;" class="btn btn-xs btn-default btn-item_remark"><i class="fa fa-pencil"></i>备注</a>';
                        return operateHtml;
                    }
                }, ]
            ]
        });
        Table.api.bindevent(table);
        //搜索
        $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
        $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
            $('.commonsearch-table').toggleClass('hidden');
        });
        $("form.form-commonsearch").off('submit').on("submit", function(event) {
            event.preventDefault();
            return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                'order_items.item_id': '=',
                'order_items.item_status': '=',
                'order_items.item_type': '=',
                'customer.ctm_id': '=',
                'customer.ctm_first_tool_id': '=',
                'customer.ctm_mobile': 'LIKE %...%',
                'customer.ctm_name': 'LIKE %...%',
                'order_items.admin_id': '=',
                'order_items.consult_admin_id': '=',
                'order_items.item_createtime': 'BETWEEN',
                'admin.dept_id': '=',
            });
        });
        $('#a-search-customer').on('click', function() {
            // Fast.api.open('customer/');
            var params = '?mode=single';
            Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
        });
        //datepicker
        Form.events.datetimepicker($("form[role=form]"));
        $('#btn-new-order').on('click', function() {
            var params = '?mode=redirect&url=' + 'cash/order/createorder' + '&field=customer_id&type=todayConsult&title=' + __('Create order');
            Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
        });
        //开手术单，开处方单，开物资单
        $('#btn-createprojectorder').on('click', function() {
            // &type=todayConsult
            var params = '?mode=redirect&url=' + 'cash/order/createprojectorder' + '&field=customer_id&title=' + __('Create order');
            Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
        });
        $('#btn-createrecipeorder').on('click', function() {
            var params = '?mode=redirect&url=' + 'cash/order/createrecipeorder' + '&field=customer_id&type=todayConsult&title=' + __('Create order');
            Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
        });
        $('#btn-createproductorder').on('click', function() {
            var params = '?mode=redirect&url=' + 'cash/order/createproductorder' + '&field=customer_id&type=todayConsult&title=' + __('Create order');
            Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
        });
        $('#btn-customer-clear').on('click', function() {
            $('#field_ctm_id').val('');
            $('#field_ctm_name').val('');
        })
        $('.btn-batch-pay').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var customerId = 0;
            var itemIdArr = new Array();
            var selections = table.bootstrapTable('getSelections');
            for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                // $.map(table.bootstrapTable('getSelections'), function(row) {
                row = selections[curIndex];
                if (customerId == 0) {
                    customerId = row.customer_id;
                } else {
                    if (customerId != row.customer_id) {
                        Layer.msg(__('Could not take orders which are not belong to single customer!'), {
                            icon: 2
                        });
                        return false;
                    }
                }
                itemIdArr.push(row.item_id);
            };
            if (itemIdArr.length == 0) {
                Layer.msg(__('Nothing selected!'), {
                    icon: 2
                });
            } else {
                var itemIdParam = itemIdArr.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.pay_url + (options.extend.pay_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + itemIdParam + '&customerId=' + customerId, __('Pay order'));
            }
        });
        return table;
    }
    return Controller;
});