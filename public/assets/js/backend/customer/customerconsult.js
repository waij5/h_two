define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'layer', 'selectpage'], function($, undefined, Backend, Table, Form, layer, selectpage) {
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
            var currentOp = '';
            var currentFilter = '';
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/customerconsult/index',
                    // add_url: 'customer/customerconsult/presearch',
                    add_url: 'customer/customer/quicksearch' + '?redirectUrl=customer/customerconsult/add',
                    r_add_url: 'customer/customerconsult/add',
                    edit_url: 'customer/customerconsult/edit',
                    del_url: 'customer/customerconsult/del',
                    multi_url: 'customer/customerconsult/multi',
                    table: 'customer_consult',
                }
            });
            var table = $("#table");
            //cst_status 0未预约， 1预约， 2已到诊， 3已过时
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'cst_id',
                sortName: 'cst_id',
                commonSearch: false,
                search: false,
                height: ($(window).height() - 40),
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentFilter = params.query.filter;
                        currentOp = params.query.op;
                    }
                },
                columns: [
                    [{
                            checkbox: true
                        },
                        {field: 'cst_id', title: 'No.', sortable: true},
                        {
                            field: 'arrive_status',
                            title: __('Arrive_status'),
                            formatter: function(value) {
                                var text = '';
                                var cssCls = '';
                                if (value == 0) {
                                    text = '<i class="fa fa-circle text-danger"></i>' + __('arrive_no');
                                } else {
                                    text = '<i class="fa fa-circle text-success"></i>' + __('arrive_yes');
                                }
                                return text;
                            }
                        }, {
                            field: 'createtime',
                            title: __('cst_updatetime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'coctime',
                            title: '最近到诊',
                            formatter: function(value) {
                                if (value != 0) {
                                    return Table.api.formatter.datetime(value);
                                }
                            },
                            sortable: true,
                        }, {
                            field: 'ctm_id',
                            title: __('Ctm_id'),
                            sortable: true
                        }, {
                            field: 'ctm_name',
                            title: __('Ctm_name')
                        }, {
                            field: 'ctm_mobile',
                            title: __('Ctm_mobile')
                        }, {
                            field: 'ctm_addr',
                            title: __('ctm_addr')
                        }, {
                            field: 'cpdt_name',
                            title: __('Cpdt_id')
                        }, {
                            field: 'dept_id',
                            title: __('dept_id')
                        }, {
                            field: 'cst_content',
                            title: __('Cst_content'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 20)
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "text-align": "left !important",
                                        "word-break": "keep-all",
                                    }
                                };
                            },
                        }, {
                            field: 'develop_staff_name',
                            title: __('developStaff')
                        }, {
                            field: 'ctm_explore',
                            title: __('ctm_explore')
                        }, {
                            field: 'ctm_source',
                            title: __('ctm_source')
                        }, {
                            field: 'admin_nickname',
                            title: __('consult_admin_name')
                        }, {
                            field: 'tool_id',
                            title: __('tool_id'),
                            formatter: function(value, row, index) {
                                if (value) {
                                    return __('accept_tool_' + value);
                                } else {
                                    return '';
                                }
                            }
                        },
                        // {field: 'cst_status', title: __('Cst_status'), formatter: Backend.api.formatter.status},
                        // {field: 'fat_name', title: __('Fat_id')},
                        {
                            field: 'coc_admin_id',
                            title: __('coc_admin_id')
                        }, {
                            field: 'book_time',
                            title: __('Book_time'),
                            formatter: function(value) {
                                if (value != 0) {
                                    return Table.api.formatter.datetime(value);
                                }
                            }
                        }, {
                            field: 'cst_status',
                            title: __('Cst_status'),
                            formatter: function(value) {
                                var text = '';
                                var cssCls = '';
                                if (value == 0) {
                                    text = '<a class="btn btn-xs btn-primary" href="javascript:;"><i class="fa fa-arrow-right"></i></a> ' + __('Status_ng');
                                } else if (value == 1) {
                                    text = '<a class="btn btn-xs btn-primary" href="javascript:;"><i class="fa fa-arrow-right"></i></a> ' + __('Status_pending');
                                } else if (value == 2) {
                                    text = '<a class="btn btn-xs btn-success" href="javascript:;"><i class="fa fa-check"></i></a> ' + __('Status_success');
                                } else if (value == 3) {
                                    text = '<a class="btn btn-xs btn-danger" href="javascript:;"><i class="fa fa-times"></i></a> ' + __('Status_outdate');
                                } else {
                                    text = '--';
                                }
                                return text;
                            }
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            // events: {
                            //     'click .btn-editone': function (e, value, row, index) {
                            //         e.stopPropagation();
                            //         $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                            //         var options = $(this).closest('table').bootstrapTable('getOptions');
                            //         Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                            //     },
                            //     'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                            // },
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            //输入关键字查找职员
            var tmpList = new Array();
            $('.nickname').keyup(function() {
                var _this = $(this);
                var keywords = $(this).val();
                $(_this).siblings().find('.word').empty().append('<div>正在加载。。。</div>');
                $.ajax({
                    url: '/customer/customer/staffquicksearch',
                    data: {
                        userName: keywords
                    },
                    dataType: 'json',
                    success: function(rows) {
                        $(_this).siblings().find('.word').empty().show();
                        tmpList = rows;
                        for (var i in rows) {
                            $(_this).siblings().find('.word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + i + '">' + rows[i] + '</li>');
                        }
                        $(_this).siblings().find('.word').show();
                    },
                    error: function() {
                        $(_this).siblings().find('.word').empty().show();
                        $(_this).siblings().find('.word').append('<div class="click_work">Fail "' + keywords + '"</div>');
                    }
                })
            })
            $('.word').on('click', 'li', function() {
                var i = $(this).data('index');
                $(this).parents().siblings('.nickname').val(tmpList[i]);
                $('#c_nickname').val(i);
            })
            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            $('.offWrap').click(function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            $('.searchSubmit').click(function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'cst.customer_id': '=',
                    'cst.cst_status': '=',
                    'cst.cpdt_id': '=',
                    'cst.dept_id': '=',
                    'cst.type_id': '=',
                    'cst.tool_id': '=',
                    'cst.cst_content': 'LIKE %...%',
                    'cst.book_time': 'BETWEEN',
                    'cst.createtime': 'BETWEEN',
                    'cst.admin_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.admin_id': '=',
                    'customer.ctm_status': '=',
                    'customer.ctm_type': '=',
                    'customer.ctm_addr': 'LIKE %...%',
                    'customer.ctm_source': '=',
                    'customer.arrive_status': '=',
                    'customer.ctm_explore': '=',
                    'customer.ctm_rank_points': 'BETWEEN',
                    'customer.ctm_pay_points': 'BETWEEN',
                    'customer.ctm_birthdate': 'BETWEEN',
                    'customer.ctm_salamt': 'BETWEEN',
                    'customer.createtime': 'BETWEEN',
                    'customer.ctm_last_recept_time': 'BETWEEN',
                    'customer.ctm_depositamt': 'BETWEEN',
                    'customer.ctm_last_rv_time': 'BETWEEN',
                    'admin.dept_id': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.potential_cpdt': '=',
                });
            });

            //导出
            $('#btn-export').on('click', function() {
                var url = '/customer/customerconsult/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });

            var parenttable = table.closest('.bootstrap-table');
            var options = table.bootstrapTable('getOptions');
            var toolbar = $(options.toolbar, parenttable);
            $(toolbar).off('click', Table.config.addbtn);
            //移除编辑按钮
            $(toolbar).find('.btn-edit').remove();
            // 添加按钮事件
            $(toolbar).find('.btn-add').before('<input type="text" class="form-control form-initial" style="margin-right: 2px" name="mobile" placeholder="' + __('Ctm_id') + '" id="ctm_id" />' + '<input type="text" class="form-control form-initial" style="margin-right: 2px" name="id" placeholder="' + __('Ctm_mobile') + '" id="ctm_phone" />');
            $(toolbar).on('click', Table.config.addbtn, function() {
                var phone = $.trim($('#ctm_phone').val());
                var id = $.trim($('#ctm_id').val());
                if (phone.length == 0 && id.length == 0) {
                    Toastr.error(__('PLz type customer id or phone!'));
                    return false;
                }
                if (phone.length) {
                    if (/^[\+]?[\d]{0,5} ?[\d]{1,11}$/.test(phone) == false) {
                        Toastr.error('请输入有效的号码');
                        return false;
                    }
                }
                var params = 'mobile=' + phone + '&id=' + id;
                Fast.api.open(options.extend.add_url + (options.extend.add_url.match(/(\?|&)+/) ? "&" : "?") + params, __('Add customer consult'));
                return false;
            });
            //接诊按钮
            $('#receive').on('click', function() {
                var val = $('#receive').html();
                if (val == '接诊') {
                    $('#f-commonsearch button[type="reset"]').click();
                    $('#cst_status').val('2');
                    $('#f-commonsearch button[type="submit"]').click();
                    $('.fixed-table-toolbar [name="commonSearch"]').click();
                    $('#receive').html('全部');
                }
                if (val == '全部') {
                    $('#f-commonsearch button[type="reset"]').click();
                    $('#f-commonsearch button[type="submit"]').click();
                    $('.fixed-table-toolbar [name="commonSearch"]').click();
                    $('#receive').html('接诊');
                }
            })
            $('#btn-show-import').on('click', function() {
                Fast.api.open('customer/customerconsult/importfromcsv', '客服记录导入');
            });
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('customer/customerconsult/downloadprocess');
        },
        add: function() {
            //新增预约/关闭预约
            $('#status-switch').bootstrapSwitch({
                onText: "预约",
                offText: "不预约",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-cst_status').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-cst_status').val(1);
                        $('#f-fat-id').addClass('hidden');
                        $('#f-book-time').removeClass('hidden');
                    } else {
                        $('#c-cst_status').val(0);
                        $('#f-fat-id').removeClass('hidden');
                        $('#f-book-time').addClass('hidden');
                    }
                }
            })
            $('#btn-redirect-consult').on('click', function() {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
                parent.Fast.api.open("customer/customerconsult/edit/ids/" + $(this).data('pk'), __('edit'));
            })
            $('#addone').on('click', function() {
                var ele = $(this);
                layer.confirm(__('A consult exists, are sure to add another by force?'), function(index, layero) {
                    window.location.href = ele.data('href');
                });
            })
            // yjyRvTypeList
            var currentRvIndex = 1;
            $('#btn-add-rvplan').on('click', function() {
                var rowHtml = $('<tr><td style="width: 160px;vertical-align: middle;text-align: center;">' + yjyRvTypeList + '</td>' + '<td  style="width: 120px;vertical-align: middle;"><input id="rvplan-' + (currentRvIndex++) + '" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="rvplan[rv_date][]" type="text"></td>' + '<td><textarea rows="2" class="form-control" name="rvplan[rv_remark][]"></textarea></td></tr>');
                $('#rvplans-table tbody').append(rowHtml);
                rowHtml.find('.datetimepicker').datetimepicker();
            })
            //提交后自动刷新订单页面
            $('#btn-refresh-order').on('click', function() {
                $('#orderHistory-table').bootstrapTable('refresh');
            });
            Backend.initConsultHistory('#conHistory-table', '#conHistory-ids', Table);
            Backend.initOsconsultHistory('#osconHistory-table', '#osconHistory-ids', Table);
            Backend.initOrderItemsHistory('#orderHistory-table', '#orderHistory-ids', Table, true);
            Backend.initHMHistory('#hmOrderHistory-table', '#orderHistory-ids', Table, true);
            /*顾客存在时 start*/
            if ($('#rvinfoHistory-ids').length) {
                Backend.initRvinfoHistory('#rvinfoHistory-table', '#rvinfoHistory-ids', Table);
                //添加回访计划
                var customerId = $('#add_rvinfo_by_plan').data('customer_id');
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
                                Toastr.success(__('Operation failed'));
                            }
                            //trigger refresh btn
                        }
                    })
                })
                document.getElementById("add_rvtype2").onclick = function(e) {
                    var ctm_id = $(this).attr('value');
                    // var fat_id = $('select[name="row[fat_id]"]').val();
                    // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                    Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
                };
                //提交后自动刷新页面
                $('#btn-refresh-rvinfo').on('click', function() {
                    $('#rvinfoHistory-table').bootstrapTable('refresh');
                });
                //rvinfo按钮点击添加 
                document.getElementById("addRvinfoHistory").onclick = function(e) {
                    // var id1 = $('#rvinfoHistory-ids').val();
                    // var id2 = $('#addRvinfoHistory').attr('value');
                    var ctm_id = $(this).attr('value');
                    Fast.api.open("customer/rvinfo/add?ctm_id=" + ctm_id, __('Add'));
                };
            } else {
                //添加回访计划
                $("#add_rvtype").onclick = function(e) {
                    var ctm_id = $(this).attr('value');
                    // var fat_id = $('select[name="row[fat_id]"]').val();
                    // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                    Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
                };
            }
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
            //点击下一步
            $('#next').on('click', function() {
                // $('#basic').removeClass('tab-pane fade in active');
                // $('#basic').addClass('tab-pane fade');
                // $('#consultinfo').removeClass('tab-pane fade');
                // $('#consultinfo').addClass('tab-pane fade in active');
                $('[href="#consultinfo"]').tab('show');
            })
            //点击返回客户资料
            $('#last').on('click', function() {
                $('[href="#basic"]').tab('show');
            })
            Controller.api.bindevent();
            Backend.initCustomerImgUpload();
        },
        edit: function() {
            //添加回访计划
            var customerId = $('#add_rvinfo_by_plan').data('customer_id');
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
                            Toastr.success(__('Operation failed'));
                        }
                        //trigger refresh btn
                    }
                })
            })
            document.getElementById("add_rvtype2").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                // var fat_id = $('select[name="row[fat_id]"]').val();
                // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
            };
            $('select[name="row[cst_status]"').on('change', function() {
                var type = $('select[name="row[cst_status]"').val();
                if (type == 1) {
                    $('#f-fat-id').addClass('hidden');
                    $('#f-book-time').removeClass('hidden');
                }
                if (type == 2) {
                    $('#f-fat-id').addClass('hidden');
                    $('#f-book-time').addClass('hidden');
                }
                if (type == 0) {
                    $('#f-fat-id').removeClass('hidden');
                    $('#f-book-time').addClass('hidden');
                }
            });
            //提交后自动刷新订单页面
            $('#btn-refresh-order').on('click', function() {
                $('#orderHistory-table').bootstrapTable('refresh');
            });
            //添加回访计划
            document.getElementById("add_rvtype").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                // var fat_id = $('select[name="row[fat_id]"]').val();
                // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
            };
            // Controller.api.changeType();
            Backend.initConsultHistory('#conHistory-table', '#conHistory-ids', Table);
            Backend.initOsconsultHistory('#osconHistory-table', '#osconHistory-ids', Table);
            Backend.initRvinfoHistory('#rvinfoHistory-table', '#rvinfoHistory-ids', Table);
            Backend.initOrderItemsHistory('#orderHistory-table', '#orderHistory-ids', Table, true);
            Backend.initHMHistory('#hmOrderHistory-table', '#orderHistory-ids', Table, true);
            // 不允许进行编辑,直接关闭页面           
            $('#close').on('click', function() {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
            //提交后自动刷新页面
            $('#btn-refresh-rvinfo').on('click', function() {
                $('#rvinfoHistory-table').bootstrapTable('refresh');
            });
            //rvinfo按钮点击添加 
            document.getElementById("addRvinfoHistory").onclick = function(e) {
                // var id1 = $('#rvinfoHistory-ids').val();
                // var id2 = $('#addRvinfoHistory').attr('value');
                var ctm_id = $(this).attr('value');
                Fast.api.open("customer/rvinfo/add?ctm_id=" + ctm_id, __('Add'));
            };
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
            Controller.api.bindevent();
            Backend.initCustomerImgUpload();
        },
        presearch: function() {
            if ($('#btn-ctm-add').length > 0) {
                $('#btn-ctm-add').on('click', function() {
                    $('#ctm_id').val('');
                    $('#edit-form').submit();
                })
                $('.btn-ctm-select').each(function() {
                    $(this).on('click', function() {
                        $('#ctm_id').val($(this).data('ctm-id'));
                        $('#edit-form').submit();
                    })
                })
            }
        },
        importfromcsv: function() {
            $('#btn-browser-file').on('click', function() {
                $('#f-cstimport').trigger('click');
            })
            $('#f-cstimport').change(function(res) {
                if ($('#f-cstimport')[0].files.length) {
                    $('#t-file-name').val($('#f-cstimport')[0].files[0].name);
                    $('#chksub').prop('disabled', false);
                } else {
                    $('#t-file-name').val('');
                    $('#chksub').prop('disabled', true);
                }
            })
            $('#chksub').on('click', function() {
                $('#chksub').prop('disabled', true);
                var form = $("form[role=form]");
                var formData = new FormData(form[0]);
                $.ajax({
                    type: "POST",
                    url: "/customer/customerconsult/importfromcsv",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(ret) {
                        $('#chksub').prop('disabled', false);
                        if (ret.code) {
                            layer.msg(ret.msg ? ret.msg : '开始导入', {
                                icon: 1
                            });
                            $('#h-record-id').val(ret.data.id);
                            //VIEW
                            Controller.api.viewImportProcess();
                        } else {
                            layer.msg(ret.msg ? ret.msg : '导入错误', {
                                icon: 2
                            });
                        }
                        return false;
                    },
                    error: function() {
                        $('#chksub').prop('disabled', false);
                    }
                });
            })
            $('#btn-regenerate').on('click', function() {
                $('#div-sub').removeClass('hidden');
                $('#div-progress').addClass('hidden');
                $('#f-cstimport').val('');
                $('#f-cstimport').trigger('change');
            })
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
                var initFlg = 0;
                $('#selector-cpdt_id').selectPage({
                    data: '/base/cproject/index',
                    // initRecord: $('[name="row[cpdt_id]"]').data('init-cpdt'),
                    params: function() {
                        return {
                            "pkey_name": "id",
                            "order_by": [
                                ["id", "ASC"],
                            ],
                            "field": "cpdt_name",
                            "yjyCustom[cpdt_status]": 1,
                        };
                    },
                    pageSize: 10,
                    showField: "cpdt_name",
                    searchField: "cpdt_name,cpdt_type",
                    keyField: 'id',
                    andOr: "OR",
                    multiple: false,
                    pagination: true,
                    showField: "cpdt_name",
                    eAjaxSuccess: function(data) {
                        return data;
                    },
                    formatItem: function(data) {
                        return data.cpdt_name + " | " + data.cpdt_type;
                    },
                    eSelect: function(data) {
                        // 回调
                        $('[name="row[dept_id]"]').val(data.dept_id);
                        $('[name="row[dept_id]"]').trigger('change');
                    },
                });
                $('[name="row[dept_id]"]').change(function() {
                    $('#show_dept_name').val($.trim($('[name="row[dept_id]"] option:selected').text()));
                });
                //初始化一次
                $('[name="row[dept_id]"]').trigger('change');
                //宽度适配
                if ($('.sp_container')) {
                    $('.sp_result_area').css('min-width', $(window).width() * 0.7);
                }
                //推荐人
                $('#a-search-customer').on('click', function() {
                    var params = '?mode=single';
                    Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
                });
                $('#btn-customer-clear').on('click', function() {
                    $('#field_ctm_id').val('');
                    $('#field_ctm_name').val('');
                });
            },
            viewImportProcess: function() {
                //重置操作
                $('#div-sub').addClass('hidden');
                $('#div-progress').removeClass('hidden');
                $('#btn-download').attr('href', '');
                $('#btn-del-download').addClass('hidden');
                $('#btn-regenerate').addClass('hidden');
                $('#btn-download').addClass('hidden');
                //恢复进度度显示
                $('#t-comp-cnt').text(0);
                $('#t-suc-cnt').text(0);
                $('#t-fail-cnt').text(0);
                $('#statusText').html('进行中');
                $('#effect-processing').removeClass('hidden');
                $('#effect-completed').addClass('hidden');
                //进度更新
                var recId = $('#h-record-id').val();
                if ($('#btn-download').hasClass('hidden')) {
                    //清除下载命令
                    $('#btn-del-download').on('click', function() {
                        var recId = $('#h-record-id').val();
                        var confirmIndex = layer.confirm(__('Are you sure to delete?'), function(index, layero) {
                            layer.load();
                            $.ajax({
                                url: 'customer/customerconsult/importfromcsv',
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    id: recId,
                                    type: 'DELETE'
                                },
                                success: function(data) {
                                    layer.closeAll('loading');
                                    layer.msg(__('Operation completed'), {
                                        icon: 1
                                    });
                                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                                },
                                error: function() {
                                    layer.closeAll('loading');
                                    layer.msg(__('Error occurs'), {
                                        icon: 2
                                    });
                                },
                            });
                        });
                    });
                    var currentCompleteCount = -2;
                    var currentFreezeTimes = 0;
                    var errorCnt = 0;
                    setTimeout(function() {
                        var processInterval = setInterval(function() {
                            $.ajax({
                                url: 'customer/customerconsult/importfromcsv',
                                dataType: 'json',
                                data: {
                                    id: recId,
                                    type: 'VIEW',
                                },
                                type: 'post',
                                success: function(data) {
                                    console.log(data);
                                    errorCnt = 0;
                                    if (typeof data.status == 'undefined') {
                                        var msg = __('Error occurs');
                                        if (typeof data.msg != 'undefined') {
                                            msg = __(data.msg);
                                        }
                                        layer.msg(msg, {
                                            icon: 2
                                        });
                                        return false;
                                    }
                                    var completedCount = parseInt(data.completedCount);
                                    var successCount = parseInt(data.successCount);
                                    var failedCount = parseInt(data.failedCount);
                                    $('#t-comp-cnt').text(completedCount);
                                    $('#t-suc-cnt').text(successCount);
                                    $('#t-fail-cnt').text(failedCount);
                                    $('#statusText').html(__(data.statusText));
                                    if (data.status == 'COMPLETED') {
                                        //没有失败 不显示下载失败结果按钮
                                        if (failedCount > 0) {
                                            $('#btn-download').attr('href', $('#btn-download').data('href') + recId);
                                            $('#btn-download').removeClass('hidden');
                                        }
                                        $('#btn-regenerate').removeClass('hidden');
                                        $('#effect-processing').addClass('hidden');
                                        $('#effect-completed').removeClass('hidden');
                                        clearInterval(processInterval);
                                    } else if (data.status == 'FAILED') {
                                        $('#btn-del-download').removeClass('hidden');
                                        $('#btn-regenerate').removeClass('hidden');
                                        clearInterval(processInterval);
                                    } else if (data.status == 'ABORT') {
                                        $('#btn-regenerate').removeClass('hidden');
                                        clearInterval(processInterval);
                                    } else {
                                        if (currentCompleteCount == completedCount) {
                                            if ((++currentFreezeTimes) >= 5) {
                                                $('#btn-del-download').removeClass('hidden');
                                            }
                                        } else {
                                            currentCompleteCount = completedCount;
                                            currentFreezeTimes = 0;
                                            $('#btn-del-download').addClass('hidden');
                                        }
                                    }
                                },
                                error: function() {
                                    if (errorCnt++ > 3) {
                                        clearInterval(processInterval);
                                    }
                                    layer.msg(__('Error occurs'), {
                                        icon: 2
                                    });
                                },
                            })
                        }, 1200);
                    }, 1500);
                }
            }
        }
    };
    return Controller;
});