define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'selectpage'], function($, undefined, Backend, Table, Form, selectpage) {
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
            var conTentHeight = $(window).height() - 50;
            $('.contentTable').css('height', conTentHeight);
            //          $('.contentRight').css('height', conTentHeight);
            $(window).resize(function() {
                var contentTableHeight = $(window).height() - 50;
                $('.contentTable').css('height', contentTableHeight);
                var conTentHeight = $('.contentTable').height();
                var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 25;
                var tableBodyHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 80;
                $('.fixed-table-body').css('height', tableBodyHeight);
                $('.tdDetail').css('height', iframeHeight);
            })
            return Controller.renderList('osconsult');
        },
        historyfordevelop: function() {
            return Controller.renderList('consult');
        },
        deductdept: function() {
            return Controller.renderList('deductdept');
        },
        bookcustomer: function() {
            return Controller.renderList('bookcustomer');
        },
        add: function() {
            Controller.api.bindevent();
            Backend.initCustomerImgUpload();
        },
        edit: function() {
            if ($('.layui-layer-footer').length != 0) {
                $('.iframeFoot').remove();
            }
            Backend.initConsultHistory('#conHistory-table', '#conHistory-ids', Table);
            Backend.initOsconsultHistory('#osconHistory-table', '#osconHistory-ids', Table);
            Backend.initRvinfoHistory('#rvinfoHistory-table', '#rvinfoHistory-ids', Table);
            Backend.initOrderItemsHistory('#orderHistory-table', '#orderHistory-ids', Table, true);
            Backend.initHMHistory('#hmOrderHistory-table', '#orderHistory-ids', Table, true);
            //当将现场客服状态选择为失败时
            $('select[name="row[osc_status]"]').on('change', function() {
                // $('select[name="row[osc_status]"]').val();
                var osc_status = $(this).val();
                if (osc_status < 0) {
                    $('#fat_id').toggleClass('hidden');
                } else {
                    $('#fat_id').addClass('hidden');
                }
            });
            //添加回访计划
            var customerId = $('#add_rvinfo_by_plan').data('customer_id');
            document.getElementById("add_rvtype2").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                // var fat_id = $('select[name="row[fat_id]"]').val();
                // Fast.api.open("customer/customerosconsult/addrvtype?osc_id="+osc_id+"&fat_id="+fat_id, __('Add'));
                Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
            };
            //添加回访计划
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
            //当客服状态为失败时关闭界面
            $('#close').on('click', function() {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
            //提交后自动刷新页面
            $('#btn-refresh-rvinfo').on('click', function() {
                $('#rvinfoHistory-table').bootstrapTable('refresh');
            });
            document.getElementById("addRvinfoHistory").onclick = function(e) {
                var ctm_id = $(this).attr('value');
                Fast.api.open("customer/rvinfo/add?ctm_id=" + ctm_id, __('Add'));
            };
            //提交后自动刷新订单页面
            $('#btn-refresh-order').on('click', function() {
                $('#orderHistory-table').bootstrapTable('refresh');
            });
            $('#btn-refresh-osconsultinfo').on('click', function() {
                location.reload();
            })
            var ctm_id = $('#h-os-customer_id').val();
            var osc_id = $("#osc_id").val();
            //回访计划tab开单
            $('#rvtypecreateorder').on('click', function() {
                Fast.api.open("cash/order/createprojectorder?customer_id=" + ctm_id + "&osc_id=" + osc_id, __('Create order'));
            });
            //开手术单，开处方单，开物资单
            $('#btn-createprojectorder').off().on('click', function() {
                Fast.api.open("cash/order/createprojectorder?customer_id=" + ctm_id, __('Create order'));
            });
            $('#btn-createrecipeorder').off().on('click', function() {
                Fast.api.open("cash/order/createrecipeorder?customer_id=" + ctm_id, __('Create order'));
            });
            $('#btn-createproductorder').off().on('click', function() {
                Fast.api.open("cash/order/createproductorder?customer_id=" + ctm_id, __('Create order'));
            });
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
                $('.sp_result_area').css('min-width', $(window).width() * 0.7 > 500 ? '500px' : $(window).width() * 0.7);
            }
            osTableHeight = $(window).height() - 204;
            $('#rvinfoHistory').find('.fixed-table-body').css('height', osTableHeight)
            Controller.api.bindevent();
            Backend.initCustomerImgUpload();
        },
        accept: function() {
            Controller.api.bindevent();
        },
        addrvtype: function() {
            Form.api.bindevent($("form[role=form]"));
        },
        quicktodaylist: function() {
            return Controller.renderList('quicktodaylist');
        },
        renderList: function(visitType) {
            // if (visitType == 'consult') {
            //     var indexUrl = 'customer/customerosconsult/historyfordevelop';
            // } else {
            //     var indexUrl = 'customer/customerosconsult/index';
            // }
            indexUrl = window.location.href;
            var currentOp = '';
            var currentFilter = '';
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: indexUrl,
                    add_url: 'customer/customerosconsult/add',
                    edit_url: 'customer/customerosconsult/edit',
                    del_url: 'customer/customerosconsult/deny',
                    accept_url: 'customer/customerosconsult/accept',
                    deny_url: 'customer/customerosconsult/deny',
                    multi_url: 'customer/customerosconsult/multi',
                    table: 'customer_osconsult',
                }
            });
            var table = $("#table");
            if (visitType == 'consult') {
                var rCols = [
                    [{
                        field: 'osc_status',
                        title: __('Osc_status'),
                        //                      formatter: yjyApi.formatter.status,
                        formatter: function(value, row, index) {
                            if (value >= 0) {
                                if (value == "3") {
                                    var orderStatus = '<lable style="color:#18bc9c">' + __('Status_' + value) + '</lable>'
                                } else {
                                    var orderStatus = __('Status_' + value)
                                }
                            } else {
                                if (value == "-2") {
                                    var value = Math.abs(value);
                                    var orderStatus = '<lable style="color:red">' + __('Status_m_' + value) + '</lable>'
                                } else {
                                    var value = Math.abs(value);
                                    var orderStatus = __('Status_m_' + value)
                                }
                            }
                            return orderStatus
                        },
                    }, {
                        field: 'createtime',
                        title: __('cst_Createtime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'osc_type',
                        title: __('Osc_type'),
                        formatter: yjyApi.formatter.oscType,
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "white-space": "nowrap",
                                }
                            }
                        }
                    }, {
                        field: 'ctm_name',
                        title: __('Ctm_name'),
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
                                $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + row.ctm_id);
                            }
                        },
                    }, {
                        field: 'ctm_id',
                        title: __('Ctm_id'),
                        class: 'cls-ctm-id'
                    }, {
                        field: 'ctm_mobile',
                        title: __('Ctm_mobile')
                    }, {
                        field: 'cst_cpdt_name',
                        title: '项目(网)',
                    }, {
                        field: 'cst_dept_name',
                        title: '科室(网)'
                    }, {
                        field: 'osc_content',
                        title: __('Osc_content'),
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "width": "25%",
                                    "min-width": "160px",
                                    "word-wrap": "normal",
                                    'text-align': 'left !important',
                                }
                            }
                        },
                    }, {
                        field: 'cpdt_name',
                        title: __('Cpdt_id')
                    }, {
                        field: 'dept_name',
                        title: __('coc_Dept_id')
                    }, {
                        field: 'admin_name',
                        title: __('Admin_id_Admin_id')
                    }, {
                        field: 'develop_admin_name',
                        title: __('Develop_admin')
                    }, {
                        field: 'ctm_salamt',
                        title: __('ctm_salamt')
                    }, {
                        field: 'ctm_depositamt',
                        title: __('ctm_depositamt')
                    }, {
                        field: 'ctm_source',
                        title: __('ctm_source')
                    }, {
                        field: 'tool_id',
                        title: __('ctm_first_tool_id'),
                        formatter: function(value, row, index) {
                            if (value) {
                                return __('accept_tool_' + value);
                            } else {
                                return '--';
                            }
                        }
                    }, {
                        field: 'ctm_explore',
                        title: __('ctm_explore')
                    }, {
                        field: 'operator_name',
                        title: __('Operator'),
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "white-space": "nowrap",
                                }
                            }
                        }
                    }, {
                        field: 'fat_name',
                        title: __('fat_id'),
                        formatter: function(value, row, index) {
                            return Backend.api.formatter.content(value, row, index, 14);
                        }
                    }, {
                        field: 'ctm_createtime',
                        title: __('ctm_createtime'),
                        formatter: Backend.api.formatter.datetime
                    }, {
                        field: 'ctm_pay_points',
                        title: __('ctm_pay_points')
                    }, ]
                ];
            } else {
                var rCols = [
                    [{
                        field: 'osc_status',
                        title: __('Osc_status'),
                        //                      formatter: yjyApi.formatter.status,
                        formatter: function(value, row, index) {
                            if (value >= 0) {
                                if (value == "3") {
                                    var orderStatus = '<lable style="color:#18bc9c">' + __('Status_' + value) + '</lable>'
                                } else {
                                    var orderStatus = __('Status_' + value)
                                }
                            } else {
                                if (value == "-2") {
                                    var value = Math.abs(value);
                                    var orderStatus = '<lable style="color:red">' + __('Status_m_' + value) + '</lable>'
                                } else {
                                    var value = Math.abs(value);
                                    var orderStatus = __('Status_m_' + value)
                                }
                            }
                            return orderStatus
                        },
                    }, {
                        field: 'createtime',
                        title: __('cst_Createtime'),
                        formatter: Table.api.formatter.datetime
                    }, {
                        field: 'osc_type',
                        title: __('Osc_type'),
                        formatter: yjyApi.formatter.oscType,
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "white-space": "nowrap",
                                }
                            }
                        }
                    }, {
                        field: 'ctm_name',
                        title: __('Ctm_name'),
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
                                $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + row.ctm_id);
                            }
                        },
                    }, {
                        field: 'ctm_id',
                        title: __('Ctm_id'),
                        class: 'cls-ctm-id'
                    }, {
                        field: 'ctm_mobile',
                        title: __('Ctm_mobile')
                    }, {
                        field: 'osc_content',
                        title: __('Osc_content'),
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "width": "25%",
                                    "min-width": "160px",
                                    "word-wrap": "normal",
                                    'text-align': 'left !important',
                                }
                            }
                        },
                    }, {
                        field: 'cpdt_name',
                        title: __('Cpdt_id')
                    }, {
                        field: 'dept_name',
                        title: __('coc_Dept_id')
                    }, {
                        field: 'admin_name',
                        title: __('Admin_id_Admin_id')
                    }, {
                        field: 'develop_admin_name',
                        title: __('Develop_admin')
                    }, {
                        field: 'ctm_salamt',
                        title: __('ctm_salamt')
                    }, {
                        field: 'ctm_depositamt',
                        title: __('ctm_depositamt')
                    }, {
                        field: 'ctm_source',
                        title: __('ctm_source')
                    }, {
                        field: 'tool_id',
                        title: __('ctm_first_tool_id'),
                        formatter: function(value, row, index) {
                            if (value) {
                                return __('accept_tool_' + value);
                            } else {
                                return '--';
                            }
                        }
                    }, {
                        field: 'ctm_explore',
                        title: __('ctm_explore')
                    }, {
                        field: 'operator_name',
                        title: __('Operator'),
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "white-space": "nowrap",
                                }
                            }
                        }
                    }, {
                        field: 'fat_name',
                        title: __('fat_id'),
                        formatter: function(value, row, index) {
                            return Backend.api.formatter.content(value, row, index, 14);
                        }
                    }, {
                        field: 'ctm_createtime',
                        title: __('ctm_createtime'),
                        formatter: Backend.api.formatter.datetime
                    }, {
                        field: 'ctm_pay_points',
                        title: __('ctm_pay_points')
                    }, ]
                ];
            }
            if (visitType == 'osconsult') {
                rCols[0].unshift({
                    field: 'osc_status',
                    title: __('Operate'),
                    table: table,
                    formatter: yjyApi.formatter.operate,
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                "white-space": "nowrap",
                            }
                        }
                    }
                });
            }
            // 初始化表格
            table.bootstrapTable({
                //关闭通用查询
                commonSearch: false,
                search: false,
                pk: 'osc_id',
                searchOnEnterKey: false,
                height: ($(window).height() - 100),
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    if (data.total != 0) {
                        var hasEdit = []
                        $('#table tr').each(function(i) {
                            var btnEdit = $(this).children('td').find('.btn-editone');
                            if (btnEdit.length > 0) {
                                hasEdit.push($(btnEdit));
                            };
                        });
                        if ($('.tdDetail').length == 0) {
                            var tdDetail = "<div class='tdDetail'><iframe  class='detailIframe'></iframe></div>"
                            $('.bootstrap-table').append(tdDetail);
                            $('.bootstrap-table .detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请点击 <strong style="color: #18bc9c">顾客姓名</strong> 或者 <strong style="color: #18bc9c">相关操作按钮</strong> 显示</p></center>');
                            var contentTableHeight = $(window).height() - 25;
                            $('.contentTable').css('height', contentTableHeight);
                            var iframeHeight = $(window).height() - 75;
                            var tableBodyHeight = $(window).height() - 185;
                            $('.fixed-table-body').css('height', tableBodyHeight);
                            if ($('.fixed-table-container').width() >= 760) {
                                $('.fixed-table-body').css('height', tableBodyHeight + 30);
                            }
                            // var firstId = $(table).find('tr[data-index="0"]').find('td.cls-ctm-id').html();
                            // $(table).find('tr[data-index="0"]').addClass('deepShow');
                            $('.fixed-table-container').css('width', '48%').css('float', 'left');
                            $('.tdDetail').css('width', '52%').css('float', 'left');
                            $('.tdDetail').css('height', iframeHeight);
                            // $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + firstId);
                        } else {
                            // var src = $('.detailIframe').attr('src');
                            // var ids = src.split('ids/')[1];
                            // for(var i = 0; i < hasEdit.length; i++) {
                            //  if($(hasEdit[i]).attr('data-pk') == ids) {
                            //      //                              var curId = hasEdit[i].parents('tr').find('td').eq(4).html();
                            //      $(table).find('tr[data-index="' + i + '"]').addClass('deepShow');
                            //      $(table).find('tr[data-index="' + i + '"]').siblings().removeClass('deepShow');
                            //      $('.detailIframe').attr('src', '/customer/customerosconsult/edit/ids/' + ids);
                            //      break;
                            //  } else {
                            //      var firstId = $(table).find('tr[data-index="0"]').find('td.cls-ctm-id').html();
                            //      $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + firstId);
                            //      $(table).find('tr[data-index="0"]').addClass('deepShow');
                            //  }
                            // }
                        }
                    }
                    var rows = $('#table tbody tr');
                    for (var i = 0; i < rows.length; i++) {
                        var curRow = rows[i];
                        var reassignBtn = $(curRow).find('.btn-editone');
                        if ($(reassignBtn).length == 0) {
                            $(curRow).find('[name="btSelectItem"]').prop('disabled', true);
                        }
                    }
                    if (data.summary) {
                        for (var i in data.summary) {
                            if ($('#' + i).length) {
                                $('#' + i).text(data.summary[i]);
                            }
                        }
                    }
                    //接受指派
                    $("#table .btn-acceptone").each(function() {
                        $(this).on('click', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('pk');
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            var btnEle = this;
                            $.ajax({
                                url: options.extend.accept_url,
                                data: {
                                    ids: id
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if (data.error) {
                                        Toastr.error(data.msg);
                                        return false;
                                    } else {
                                        if (data.flag) {
                                            table.bootstrapTable('refresh');
                                            return;
                                        }
                                        //在同时打开多个页面，一个页面接受后，另一个页面再点接受方会触发此处
                                        var index = Layer.confirm(__('You have accepted this consult, do you want to open the edit window?'), {
                                            icon: 3,
                                            title: __('Warning'),
                                            shadeClose: true
                                        }, function() {
                                            var table = $(btnEle).closest('table');
                                            var options = table.bootstrapTable('getOptions');
                                            Layer.close(index);
                                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + id, __('Accept'));
                                        });
                                    }
                                }
                            });
                        })
                    })
                    // var refreshTimer = setInterval(function() {
                    //     // $('#toolbar .btn-refresh').trigger('click');
                    //     table.bootstrapTable('refresh');
                    // }, 60000);
                    $("#table .btn-editone").each(function() {
                        $(this).on('click', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('pk');
                            $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                            $('.detailIframe').attr('src', '/customer/customerosconsult/edit/ids/' + id);
                        })
                    })
                    //完工
                    $("#table .btn-denyone").each(function() {
                        $(this).on('click', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('pk');
                            var that = this;
                            var index = Layer.confirm(__('Are you sure you want to deny this consult?'), {
                                icon: 3,
                                title: __('Warning'),
                                shadeClose: true
                            }, function() {
                                var table = $(that).closest('table');
                                var options = table.bootstrapTable('getOptions');
                                Table.api.multi("del", id, table, that);
                                table.bootstrapTable('refresh');
                                Layer.close(index);
                            });
                        })
                    })
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentFilter = params.query.filter;
                        currentOp = params.query.op;
                    }
                },
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'osc_id',
                sortName: 'osc_id',
                sortOrder: 'DESC',
                buttons: [{
                    name: 'deny',
                    icon: 'fa fa-pencil',
                    classname: 'btn btn-xs btn-success btn-editone'
                }, ],
                columns: rCols,
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:5px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
                //              var conTentHeight = $('.contentTable').css('height');
                //              var conTentHeight = $('.contentTable').height() - $('#ribbon').height() - $('.content').offset().top - parseInt($('.content').css('padding-top'));
                //              var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 30;
                //              var tableHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 40;
                //              $('.fixed-table-body').css('height', tableHeight);
                //              $('.tdDetail').css('height', iframeHeight);
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
                    'coc.customer_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.old_ctm_code': '=',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_type': '=',
                    'customer.ctm_depositamt': 'BETWEEN',
                    'customer.ctm_salamt': 'BETWEEN',
                    'customer.ctm_birthdate': 'BETWEEN',
                    'customer.ctm_last_recept_time': 'BETWEEN',
                    'customer.ctm_last_rv_time': 'BETWEEN',
                    'cst.tool_id': '=',
                    'customer.ctm_status': '=',
                    'coc.admin_id': '=',
                    'coc.cpdt_id': '=',
                    'coc.dept_id': '=',
                    'coc.osc_status': '=',
                    'coc.osc_type': '=',
                    'cst.fat_id': '=',
                    'coc.createtime': 'BETWEEN',
                    'coc.osc_content': 'LIKE %...%',
                    'customer.admin_id': '=',
                    'customer.ctm_pay_points': 'BETWEEN',
                    'customer.ctm_source': '=',
                    'customer.ctm_explore': '=',
                    'cst.type_id': '=',
                    'cst.admin_id': '=',
                    'admin.dept_id': '=',
                    'customer.createtime': 'BETWEEN',
                    'customer.potential_cpdt': '=',
                });
            });
            $('#btn-export').on('click', function() {
                var url = '/customer/customerosconsult/downloadprocess' + '?type=' + visitType + '&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('customer/customerosconsult/downloadprocess');
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
                //客户
                $('#a-search-customer').on('click', function() {
                    var params = '?mode=single';
                    Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
                });
                $('#btn-customer-clear').on('click', function() {
                    $('#field_ctm_id').val('');
                    $('#field_ctm_name').val('');
                });
                //刷新统计人数时会重复刷新，将单独的table刷新off
                var toolbar = $('#toolbar', $("#table").closest('.bootstrap-table'));
                //              $(toolbar).off('click', ".btn-refresh").on('click', ".btn-refresh", function () {
                //                 $('#numInfo').bootstrapTable('refresh'); 
                // table.bootstrapTable('refresh'); 
                //              });
            }
        }
    };
    var yjyApi = {
        formatter: {
            operate: function(value, row, index) {
                var table = this.table;
                // 操作配置
                var options = table ? table.bootstrapTable('getOptions') : {};
                var html = [];
                var buttons = [];
                if (row.osc_status == '0') {
                    buttons.push({
                        name: 'accept',
                        icon: 'fa fa-check',
                        classname: 'btn btn-xs btn-success btn-acceptone',
                        title: __('Accept')
                    });
                    buttons.push({
                        name: 'deny',
                        icon: 'fa fa-times',
                        classname: 'btn btn-xs btn-danger btn-denyone',
                        title: __('Deny')
                    });
                } else if (row.osc_status != -3) {
                    //？调整必要， -3中止， 是否对其它已结束的禁止修改
                    buttons.push({
                        name: 'edit',
                        icon: 'fa fa-pencil',
                        classname: 'btn btn-xs btn-success btn-editone',
                        title: __('Edit')
                    });
                } else {
                    return '-';
                }
                $.each(buttons, function(i, j) {
                    var attr = table.data("operate-" + j.name);
                    //自动加上ids
                    j.url = j.url ? j.url + (j.url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] : '';
                    url = j.url ? Fast.api.fixurl(j.url) : 'javascript:;';
                    classname = j.classname;
                    icon = j.icon ? j.icon : '';
                    text = j.text ? j.text : '';
                    title = j.title ? j.title : text;
                    html.push('<a href="' + url + '" class="' + classname + '" title="' + title + '" data-pk="' + row[options.pk] + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
                });
                return html.join(' ');
            },
            status: function(value, row, index) {
                if ((value = parseInt(value)) == NaN) {
                    return '--';
                }
                $key = 'Status_';
                if (value < 0) {
                    $key += 'm_';
                }
                $key = $key + Math.abs(value);
                return __($key);
            },
            oscType: function(value) {
                var value = parseInt(value);
                if (value == NaN || value == 0) {
                    return '-';
                } else {
                    if (value == 1) {
                        return '<span class="text-success">' + __('osc_type_' + value) + '</span>';
                    }
                    if (value == 2) {
                        return '<span class="text-danger">' + __('osc_type_' + value) + '</span>';
                    }
                }
                return __('osc_type_' + value);
            },
        },
    }
    return Controller;
});