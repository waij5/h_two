define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'selectpage'], function($, undefined, Backend, Table, Form, selectpage) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/customerreception/index',
                    // add_url: 'customer/customerreception/presearch',
                    add_url: 'customer/customer/quicksearch' + '?redirectUrl=customer/customerreception/add',
                    // edit_url: 'customer/customerreception/edit',
                    // del_url: 'customer/customerreception/del',
                    edit_url: 'customer/customerreception/reassign',
                    del_url: 'customer/customerreception/del',
                    // close_url: 'customer/customerreception/close',
                    multi_url: 'customer/customerreception/multi',
                    table: 'customer_osconsult',
                }
            });
            var table = $("#table");
            let customerInfoUrl = $(table).data('operate-edit') ? '/customer/customer/edit/ids/' : '/cash/order/deductview/ids/';
            // 初始化表格
            var indexBootTable = table.bootstrapTable({
                //关闭通用查询
                commonSearch: false,
                search: false,
                searchOnEnterKey: false,
                height: ($(window).height() - 90),
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    var rows = $('#table tbody tr');
                    for (var i = 0; i < rows.length; i++) {
                        var curRow = rows[i];
                        var reassignBtn = $(curRow).find('.btn-editone');
                        if ($(reassignBtn).length == 0) {
                            $(curRow).find('[name="btSelectItem"]').prop('disabled', true);
                        }
                    }
                    //初复诊人数
                    if (data.summary) {
                        for (var i in data.summary) {
                            if ($('#' + i).length) {
                                $('#' + i).text(data.summary[i]);
                            }
                        }
                    }
                    //改派
                    $("#table .btn-editone").each(function() {
                        $(this).on('click', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('pk');
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + id, __('Edit'));
                        })
                    })
                    //完工
                    $("#table .btn-delone").each(function() {
                        $(this).on('click', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('pk');
                            var that = this;
                            var top = $(that).offset().top - $(window).scrollTop();
                            var left = $(that).offset().left - $(window).scrollLeft() - 260;
                            if (top + 154 > $(window).height()) {
                                top = top - 154;
                            }
                            if ($(window).width() < 480) {
                                top = left = undefined;
                            }
                            var index = Layer.confirm(__('Are you sure you want to close this consult?'), {
                                icon: 3,
                                title: __('Warning'),
                                offset: [top, left],
                                shadeClose: true
                            }, function() {
                                var table = $(that).closest('table');
                                var options = table.bootstrapTable('getOptions');
                                Table.api.multi("del", id, table, that);
                                Layer.close(index);
                            });
                        })
                    })
                },
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'osc_id',
                sortName: 'osc_id',
                sortOrder: 'DESC',
                columns: [
                    [{
                            checkbox: true
                        }, {
                            field: 'osc_status',
                            title: __('Osc_status'),
                            formatter: yjyApi.formatter.status
                        }, {
                            field: 'osc_type',
                            title: __('Osc_type'),
                            formatter: yjyApi.formatter.oscType
                        }, {
                            field: 'ctm_id',
                            title: __('Ctm_id')
                        },
                        // {field: 'osc_id', title: __('Osc_id')},
                        {
                            field: 'ctm_name',
                            title: __('Ctm_name'),
                            formatter: function(value, row, index) {
                                return '<a class="btn-clickviewsoneInfo" title="点击查看顾客信息">' + value + '</a>';
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
                                    Fast.api.open(customerInfoUrl + row.ctm_id, '顾客资料');
                                }
                            },
                        },
                        {
                            field: 'admin_dept_name',
                            title: __('admin_dept_name')
                        }, {
                            field: 'develop_admin_name',
                            title: __('Develop_admin')
                        }, {
                            field: 'admin_name',
                            title: __('Admin_id')
                        }, {
                            field: 'service_admin_name',
                            title: __('service_admin_name')
                        }, {
                            field: 'operator_name',
                            title: __('Operator')
                        },
                        // {field: 'osc_content', title: __('Osc_content')},
                        // {field: 'osc_content', title: __('Osc_content'), formatter: Backend.api.formatter.content},
                        {
                            field: 'cpdt_name',
                            title: __('cpdt_id')
                        }, {
                            field: 'ctm_source',
                            title: __('ctm_source')
                        },{
                            field: 'ctm_first_tool_id',
                            title: __('ctm_first_tool_id'),
                            formatter: function(value, row, index) {
                                if (value) {
                                    return __('accept_tool_' + value);
                                } else {
                                    return '';
                                }
                            }
                        },{
                            field: 'createtime',
                            title: __('Createtime'),
                            formatter: Table.api.formatter.datetime
                        },
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        // {field: 'osc_status', title: __('Operate'), table: table, formatter: yjyApi.formatter.operate}
                        {
                            field: 'osc_status',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-editone': Table.api.events.operate['click .btn-editone'],
                                'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                            },
                            formatter: function(value, row, index) {
                                if (value == 0 || value == -1) {
                                    var table = this.table;
                                    // 操作配置
                                    var options = table ? table.bootstrapTable('getOptions') : {};
                                    // 默认按钮组
                                    var buttons = $.extend([], this.buttons || []);
                                    // buttons.push({name: 'dragsort', icon: 'fa fa-arrows', classname: 'btn btn-xs btn-primary btn-dragsort'});
                                    buttons.push({
                                        name: 'edit',
                                        icon: 'fa fa-pencil',
                                        classname: 'btn btn-xs btn-success btn-editone',
                                        title: __('Edit')
                                    });
                                    // buttons.push({name: 'close', icon: 'fa fa-trash', classname: 'btn btn-xs btn-danger btn-closeone', title: __('Close')});
                                    buttons.push({
                                        name: 'del',
                                        icon: 'fa fa-trash',
                                        classname: 'btn btn-xs btn-danger btn-delone',
                                        title: __('Del')
                                    });
                                    var html = [];
                                    $.each(buttons, function(i, j) {
                                        var attr = table.data("operate-" + j.name);
                                        // if ((typeof attr === 'undefined' || attr) || (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] == 'undefined')) {
                                        if (['add', 'edit', 'del', 'multi', 'close'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
                                            return true;
                                        }
                                        //自动加上ids
                                        j.url = j.url ? j.url + (j.url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] : '';
                                        url = j.url ? Fast.api.fixurl(j.url) : 'javascript:;';
                                        classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
                                        icon = j.icon ? j.icon : '';
                                        text = j.text ? j.text : '';
                                        title = j.title ? j.title : text;
                                        html.push('<a href="' + url + '" class="' + classname + '" title="' + title + '" data-pk="' + row[options.pk] + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
                                        // }
                                    });
                                    return html.join(' ');
                                }
                            },
                        }
                        // formatter: yjyApi.formatter.operate}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'customer.ctm_id': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_source': '=',
                    'admin.dept_id': '=',
                    'customer.admin_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'coc.customer_id': '=',
                    'coc.osc_status': '=',
                    'coc.osc_type': '=',
                    'coc.dept_id': '=',
                    'coc.admin_dept_id': '=',
                    'coc.admin_id': '=',
                    'coc.service_admin_id': '=',
                    'coc.createtime': 'BETWEEN',
                });
            });
            //attention after Table.api.bindevent(table)
            var parenttable = table.closest('.bootstrap-table');
            var options = table.bootstrapTable('getOptions');
            var toolbar = $(options.toolbar, parenttable);
            $(toolbar).off('click', Table.config.addbtn);
            // 添加按钮事件
            $(toolbar).find('.btn-add').before('<input type="text" class="form-control form-initial" style="margin-right: 2px" name="ctm_id" placeholder="' + __('Ctm_id/book number') + '" id="ctm_id" />' + '<input type="text" class="form-control form-initial" style="margin-right: 2px" name="old_ctm_code" placeholder="' + '宏脉卡号' + '" id="old_ctm_code" />' + '<input type="text" class="form-control form-initial" style="margin-right: 2px" name="mobile" placeholder="' + __('Ctm_mobile') + '" id="ctm_phone" />');
            $(toolbar).on('click', Table.config.addbtn, function() {
                var phone = $.trim($('#ctm_phone').val());
                var id = $.trim($('#ctm_id').val());
                var oldCtmCode = $.trim($('#old_ctm_code').val());
                if (phone.length == 0 && id.length == 0 && oldCtmCode.length == 0) {
                    Toastr.error('卡号，宏脉卡号，手机号码 请至少输入一个');
                    return false;
                }
                if (phone.length) {
                    if (/^[\+]?[\d]{0,5} ?[\d]{1,11}$/.test(phone) == false) {
                        Toastr.error('请输入有效的号码');
                        return false;
                    }
                }
                var params = 'mobile=' + phone + '&id=' + id + '&old_ctm_code=' + oldCtmCode;
                Fast.api.open(options.extend.add_url + (options.extend.add_url.match(/(\?|&)+/) ? "&" : "?") + params, __('Customer recept'));
                return false;
            });

            $(toolbar).on('click', '.btn-search-customer', function() {
                Fast.api.open('/customer/customer/comselectpop?mode=redirect&url=customer/customerreception/add&field=customer_id&title=顾客接待&dialog=1', '选择顾客');
            })
        },
        add: function() {
            //点击下一步
            $('#next').on('click', function() {
                $('[href="#assignosconsult"]').tab('show');
            })
            //点击返回客户资料
            $('#last').on('click', function() {
                $('[href="#basic"]').tab('show');
            })
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
            
            $('#addone').on('click', function() {
                parent.Fast.api.open($(this).data('force-url'), __('Customer recept'));
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
            $('#btn-reassign').on('click', function() {
                parent.Fast.api.open('/customer/customerreception/reassign/ids/' + $(this).data('pk'), __('Customer recept'));
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
            Backend.initCustomerImgUpload();
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        //attention
        reassign: function() {
            Controller.api.bindevent();
        },
        presearch: function() {
            //attention don't bindevent
            // Controller.api.bindevent();
            if ($('#btn-ctm-add').length > 0) {
                $('#btn-ctm-add').on('click', function() {
                    $('#ctm_id').val('');
                    $('consult_id').val('');
                    $('#edit-form').submit();
                })
                $('.btn-ctm-select').each(function() {
                    $(this).on('click', function() {
                        var ctmId = $(this).data('ctm-id');
                        $('#ctm_id').val(ctmId);
                        var sConsultId = '#s-consult-' + ctmId;
                        if ($(sConsultId).length > 0) {
                            $('#consult_id').val($(sConsultId).val());
                        } else {
                            $('#consult_id').val('');
                        }
                        $('#edit-form').submit();
                    })
                })
            }
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
                Form.api.bindevent($("form[role=form]", null, null, function() {
                    return false;
                }));
                //刷新统计人数时会重复刷新，将单独的table刷新off
                var toolbar = $('#toolbar', $("#table").closest('.bootstrap-table'));
                $(toolbar).off('click', ".btn-refresh").on('click', ".btn-refresh", function() {
                    window.location.reload();
                    // table.bootstrapTable('refresh'); 
                });
                // 通过选择客服项目联动出客服科室
                // $("#c-cpdt_id").on('change',function(){
                //     Controller.api.changeType();
                // })
                //推荐人
                $('#a-search-customer').on('click', function() {
                    // Fast.api.open('customer/');
                    var params = '?mode=single';
                    Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
                });
                $('#btn-customer-clear').on('click', function() {
                    $('#field_ctm_id').val('');
                    $('#field_ctm_name').val('');
                });
            },
        }
    };

    function doSearch(form) {
        return false;
    }
    var yjyApi = {
        formatter: {
            operate: function(value, row, index) {
                if (value == 0 || value == -1) {
                    var table = this.table;
                    // 操作配置
                    var options = table ? table.bootstrapTable('getOptions') : {};
                    // 默认按钮组
                    var buttons = $.extend([], this.buttons || []);
                    // buttons.push({name: 'dragsort', icon: 'fa fa-arrows', classname: 'btn btn-xs btn-primary btn-dragsort'});
                    buttons.push({
                        name: 'edit',
                        icon: 'fa fa-pencil',
                        classname: 'btn btn-xs btn-success btn-editone',
                        title: __('Edit')
                    });
                    buttons.push({
                        name: 'close',
                        icon: 'fa fa-trash',
                        classname: 'btn btn-xs btn-danger btn-closeone',
                        title: __('Close')
                    });
                    var html = [];
                    $.each(buttons, function(i, j) {
                        var attr = table.data("operate-" + j.name);
                        if ((typeof attr === 'undefined' || attr) || (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] == 'undefined')) {
                            if (['add', 'edit', 'del', 'multi'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
                                return true;
                            }
                            //自动加上ids
                            j.url = j.url ? j.url + (j.url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] : '';
                            url = j.url ? Fast.api.fixurl(j.url) : 'javascript:;';
                            classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
                            icon = j.icon ? j.icon : '';
                            text = j.text ? j.text : '';
                            title = j.title ? j.title : text;
                            html.push('<a href="' + url + '" class="' + classname + '" title="' + title + '" data-pk="' + row[options.pk] + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
                        }
                    });
                    return html.join(' ');
                }
            },
            status: function(value, row, index) {
                if ((value = parseInt(value)) == NaN) {
                    return '--';
                }
               
                if (value >= 0) {
                    if (value == "3"){
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
                
                return orderStatus;
            },
            oscType: function(value) {
                var value = parseInt(value);
                if (value == NaN || value == 0) {
                    return '-';
                }
                if (value == 1) {
                    return '<span class="text-success">' + __('osc_type_' + value) + '</span>';
                }
                if (value == 2) {
                    return '<span class="text-danger">' + __('osc_type_' + value) + '</span>';
                }
                return __('osc_type_' + value);
            },
        },
    }
    return Controller;
});