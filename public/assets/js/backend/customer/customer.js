define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'fast', 'selectpage'], function($, undefined, Backend, Table, Form, fast, selectpage) {
    var Controller = {
        index: function() {
            // $('button[type="reset"]').click(function() {
            //     $('.bootstrap-select').each(function(index) {
            //         var searchId = $(this).find('.selectpicker').attr('name');
            //         var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
            //         $(this).find('.dropdown-toggle').attr('title', defaultVal).attr('data-id', searchId).removeClass('bs-placeholder');
            //         $(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
            //         $(this).find('.inner').find('li').eq(0).addClass('selected active');
            //         $(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
            //         $(this).find('.inner').find('li').removeClass('hidden');
            //     })
            // })
            // var conTentHeight = $(window).height() - $('#ribbon').height() - $('.content').offset().top - parseInt($('.content').css('padding-top'));
            // $('.contentTable').css('height', conTentHeight);
            // //          $('.contentRight').css('height', conTentHeight);
            // $(window).resize(function() {
            //     var contentTableHeight = $(window).height() - 45;
            //     $('.contentTable').css('height', contentTableHeight);
            //     var conTentHeight = $('.contentTable').height() - 45;
            //     var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 30;
            //     var tableHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 30;
            //     $('.fixed-table-body').css('height', tableHeight);
            //     $('.tdDetail').css('height', iframeHeight);
            // })
            return Controller.renderList('index');
        },
        search: function() {
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
            var conTentHeight = $(window).height() - $('#ribbon').height() - $('.content').offset().top - parseInt($('.content').css('padding-top'));
            $('.contentTable').css('height', conTentHeight);
            //          $('.contentRight').css('height', conTentHeight);
            $(window).resize(function() {
                var contentTableHeight = $(window).height() - 45;
                $('.contentTable').css('height', contentTableHeight);
                var conTentHeight = $('.contentTable').height() - 45;
                var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 30;
                var tableHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 30;
                $('.fixed-table-body').css('height', tableHeight);
                $('.tdDetail').css('height', iframeHeight);
            })
            return Controller.renderList('search');
        },
        publist: function() {
            return Controller.renderList('publist');
        },
        invalid: function() {
            return Controller.renderList('invalid');
        },
        cstpublist: function() {
            return Controller.renderList('cstpublist');
        },
        mycstlist: function() {
            return Controller.renderList('mycstlist');
        },
        listofbirth: function() {
            $('[name=ctm_birthdate_start]').parent('div').css('display', 'none');
            return Controller.renderList('listofbirth');
        },
        listforosconsult: function() {
            return Controller.renderList('listforosconsult');
        },
        publistfordeveloper: function() {
            return Controller.renderList('publistfordeveloper');
        },
        deptcustomerlist: function() {
            Table.api.init({
                extend: {
                    index_url: 'customer/customer/deptcustomerlist',
                    edit_url: 'customer/customer/edit/viewonly/1',
                    table: 'customer',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                commonSearch: false,
                search: false,
                pk: 'ctm_id',
                sortName: 'ctm_id',
                columns: [
                    [{
                            field: 'ctm_id',
                            title: __('Ctm_id')
                        }, {
                            field: 'ctm_name',
                            title: __('Ctm_name')
                        }, {
                            field: 'ctm_sex',
                            title: __('Ctm_sex')
                        }, {
                            field: 'ctm_addr',
                            title: __('Ctm_addr'),
                        },
                        // {
                        //     field: 'ctm_mobile',
                        //     title: __('Ctm_mobile')
                        // }, {
                        //     field: 'ctm_explore',
                        //     title: __('Ctm_explore')
                        // }, {
                        //     field: 'ctm_source',
                        //     title: __('Ctm_source')
                        // },
                        {
                            field: 'ctm_job',
                            title: __('Ctm_job')
                        },
                        // {
                        //     field: 'developStaffName',
                        //     title: __('developStaff'),
                        // }, 
                        {
                            field: 'ctm_remark',
                            title: __('Ctm_remark'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 20);
                            },
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    Fast.api.open('cash/order/deductview/ids/' + row.ctm_id, __('Customer info'));
                                    // Fast.api.open('customer/customer/edit/viewonly/1/ids/' + row.ctm_id, __('Customer info'));
                                }
                            },
                            formatter: function(value, row, index) {
                                return '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title=""><i class="fa fa-pencil"></i></a>';
                            }
                        }
                    ]
                ],
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'order_items.item_createtime': 'BETWEEN',
                    'customer_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                });
            });
        },
        mergehiscustomer: function() {
            // const successCallback = (data, ret) => {
            //     var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation completed');
            //     parent.Toastr.success(msg);
            //     parent.$(".btn-refresh").trigger("click");
            //     var index = parent.Layer.getFrameIndex(window.name);
            //     parent.Layer.close(index);
            //     return false;
            // }
            // Form.api.bindevent($("form[role=form]"), );
            let form = $("form[role=form]");
            let events = Form.events;
            events.bindevent(form);
            // events.validator(form, success, error, submit);
            events.selectpicker(form);
            events.selectpage(form);
            events.cxselect(form);
            events.citypicker(form);
            events.datetimepicker(form);
            events.plupload(form);
            events.faselect(form);
            // Controller.api.bindevent();
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Backend.initConsultHistory('#conHistory-table', '#conHistory-ids', Table);
            Backend.initOsconsultHistory('#osconHistory-table', '#osconHistory-ids', Table);
            Backend.initRvinfoHistory('#rvinfoHistory-table', '#rvinfoHistory-ids', Table);
            Backend.initOrderItemsHistory('#orderHistory-table', '#orderHistory-ids', Table, true);
            Backend.initHMHistory('#hmOrderHistory-table', '#orderHistory-ids', Table, true);
            //拨打电话按钮
            $('#addphone').on('click', function() {
                //被呼叫号码
                var phoneNumber = $('#phoneNumber').val();
                var Exten = phoneNumber;
                //坐席工号
                var FromExten = 8000;
                $.ajax({
                    url: '/customer/customer/callPhone',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        FromExten: FromExten,
                        Exten: Exten
                    },
                    success: function(data) {
                        console.log(123);
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
            //提交后自动刷新页面
            $('#btn-refresh-rvinfo').on('click', function() {
                $('#rvinfoHistory-table').bootstrapTable('refresh');
            });
            //rvinfo按钮点击添加 
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
                            Toastr.success(__('Operation failed'));
                        }
                        //trigger refresh btn
                    }
                })
            })
            $('.word').css('width', $('#staffSearch').css('width'));
            var tmpList = new Array();
            $('#staffSearch').on('keyup', function() {
                var _this = $(this);
                $('#h-develop-id').val('');
                $('.word').empty();
                $('.word').show();
                $.ajax({
                    url: '/customer/customer/reassigndev',
                    data: {
                        type: 'search',
                        userName: _this.val()
                    },
                    dataType: 'json',
                    // jsonpCallback: 'fun', //回调函数名(值) value
                    beforeSend: function() {
                        $('.word').append('<div>正在加载。。。</div>');
                    },
                    success: function(data) {
                        tmpList = data;
                        $('.word').empty();
                        for (var i in tmpList) {
                            $(_this).siblings().find('.word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-key="' + i + '">' + tmpList[i] + '</li>');
                        }
                    },
                    error: function() {
                        $('.word').append('<div class="click_work">Fail "' + keywords + '"</div>');
                    }
                })
            })
            $('.word').on('click', 'li', function() {
                var key = $(this).data('key');
                $('#staffSearch').val(tmpList[key]);
                $('#h-develop-id').val(key);
                $('.word').hide();
            })
            //修改手机号
            $('#modify_mobile').on('click', function() {
                var newmobile = $('#c-ctm_mobile').val();
                $.ajax({
                    url: 'customer/customer/customerMobile',
                    data: {
                        newmobile: newmobile,
                        customerId: customerId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.code) {
                            var msg = res.msg ? res.msg : __('Operation completed');
                            Toastr.success(res.msg);
                        } else {
                            var msg = res.msg ? res.msg : __('Operation failed');
                            Toastr.error(res.msg);
                        }
                    }
                })
            });
            //修改推荐人
            $('#modify_recCtmId').on('click', function() {
                var newrecCtmId = $('#field_ctm_id').val();
                var newrecCtmName = $('#field_ctm_name').val();
                $.ajax({
                    url: 'customer/customer/RecCtmId',
                    data: {
                        newrecCtmId: newrecCtmId,
                        customerId: customerId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.code) {
                            var msg = res.msg ? res.msg : __('Operation completed');
                            Toastr.success(res.msg);
                            $('#c-rec_customer_id').text(newrecCtmName);
                        } else {
                            var msg = res.msg ? res.msg : __('Operation failed');
                            Toastr.error(res.msg);
                        }
                    }
                });
            });
            //修改营销人员
            $('#btn-reassign').on('click', function() {
                var newDevStaff = $('#h-develop-id').val();
                if (newDevStaff) {
                    var selects = document.getElementById("h-develop-id"); //获取选中的select
                    var indexs = selects.selectedIndex; //option选中项的索引
                    var newDevStaffName = selects.options[indexs].text; //选中option的值
                    var url = '/customer/customer/reassigndev' + '?type=normal&customerId=' + customerId + '&newDevStaff=' + newDevStaff;
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function(data) {
                            if (data.code) {
                                var msg = data.msg ? data.msg : __('Operation completed');
                                Toastr.success(data.msg);
                                $('#div-staffName').text(newDevStaffName);
                            } else {
                                Toastr.error(__('Operation failed'));
                            }
                        }
                    })
                } else {
                    Layer.msg(__('Invalid develop staff!'), {
                        icon: 2
                    });
                }
            })
            $('#btn-close').on('click', function() {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
            osTableHeight = $(window).height() - 204;
            $('#rvinfoHistory').find('.fixed-table-body').css('height', osTableHeight)
            //          if ($('.contentRight').length == 0) {
            //              $('.iframeFoot').remove();
            //          }
            Controller.api.bindevent();
            Backend.initCustomerImgUpload();
        },
        batchupdateosc: function() {
            Controller.api.bindevent();
            Form.events.datetimepicker($('.form-commonsearch'));
            Form.events.selectpicker($("form[role=form]"));
            $('#status-sync_cst_admin').bootstrapSwitch({
                onText: "是",
                offText: "否",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-sync_cst_admin').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-sync_cst_admin').val(1);
                    } else {
                        $('#c-sync_cst_admin').val(0);
                    }
                }
            });
            $('#status-sync_order_admin').bootstrapSwitch({
                onText: "正常",
                offText: "禁用",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-sync_order_admin').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-sync_order_admin').val(1);
                        $('#h-range_notice').removeClass('hidden');
                        $('#h-range_area').removeClass('hidden');
                    } else {
                        $('#c-sync_order_admin').val(0);
                        $('#h-range_notice').addClass('hidden');
                        $('#h-range_area').addClass('hidden');
                    }
                }
            });
            var rangeMode = '';
            $('.btn-group-range .btn').each(function() {
                $(this).on('click', function() {
                    rangeMode = $(this).data('range-mode');
                    if (rangeMode == 'set') {
                        $('#h-range_time').removeClass('hidden');
                        $('#text_range_mode').text('');
                    } else {
                        $('#h-range_time').addClass('hidden');
                        $('#text_range_mode').text($.trim($(this).html()));
                    }
                });
            });
            $('#btn-submit').on('click', function() {
                $('#btn-submit').prop('disabled', true);
                $.post({
                    url: '/customer/customer/batchupdateosc',
                    data: {
                        oscAdminId: $('[name=oscAdminId]').val(),
                        ids: $('[name=ids]').val(),
                        //是否同步订单
                        syncOrderAdmin: $('#c-sync_order_admin').val(),
                        //同步订单范围模式
                        rangeMode: rangeMode,
                        ////同步订单范围--仅自定义时有效
                        item_createtime_start: $("input[name='item_createtime_start']").val(),
                        item_createtime_end: $("input[name='item_createtime_end']").val(),
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
        batchinvalidout: function() {
            $('#btn-close').on('click', function() {
                parent.Layer.close(parent.Layer.getFrameIndex(window.name));
            });
            Controller.api.bindevent();
        },
        batchpublicout: function() {
            $('#btn-close').on('click', function() {
                parent.Layer.close(parent.Layer.getFrameIndex(window.name));
            });
            Controller.api.bindevent();
        },
        adminid: function() {
            Form.events.datetimepicker($('.form-commonsearch'));
            Form.events.selectpicker($("form[role=form]"));
            $('#status-sync_cst_admin').bootstrapSwitch({
                onText: "是",
                offText: "否",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-sync_cst_admin').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-sync_cst_admin').val(1);
                    } else {
                        $('#c-sync_cst_admin').val(0);
                    }
                }
            });
            $('#status-sync_order_admin').bootstrapSwitch({
                onText: "正常",
                offText: "禁用",
                onColor: "success",
                offColor: "danger",
                size: "small",
                //初始开关状态
                state: $('#c-sync_order_admin').val() == 1 ? true : false,
                onSwitchChange: function(event, state) {
                    if (state == true) {
                        $('#c-sync_order_admin').val(1);
                        $('#h-range_notice').removeClass('hidden');
                        $('#h-range_area').removeClass('hidden');
                    } else {
                        $('#c-sync_order_admin').val(0);
                        $('#h-range_notice').addClass('hidden');
                        $('#h-range_area').addClass('hidden');
                    }
                }
            });
            var rangeMode = '';
            $('.btn-group-range .btn').each(function() {
                $(this).on('click', function() {
                    rangeMode = $(this).data('range-mode');
                    if (rangeMode == 'set') {
                        $('#h-range_time').removeClass('hidden');
                        $('#text_range_mode').text('');
                    } else {
                        $('#h-range_time').addClass('hidden');
                        $('#text_range_mode').text($.trim($(this).html()));
                    }
                });
            });
            Controller.api.bindevent();
            $('#btn-submit').on('click', function() {
                $('#btn-submit').prop('disabled', true);
                $.post({
                    url: '/customer/customer/adminid',
                    data: {
                        adminid: $('[name=adminid]').val(),
                        id: $('[name=id]').val(),
                        // cst_id: $('input:radio:checked').val(),
                        // cst_id: $("input[name='cst_id']:checked").val(),
                        consult_admin_id: $("input[name='consult_admin_id']:checked").val(),
                        //是否同步受理人员
                        syncCstAdmin: $('#c-sync_cst_admin').val(),
                        //是否同步订单
                        syncOrderAdmin: $('#c-sync_order_admin').val(),
                        //同步订单范围模式
                        rangeMode: rangeMode,
                        ////同步订单范围--仅自定义时有效
                        item_createtime_start: $("input[name='item_createtime_start']").val(),
                        item_createtime_end: $("input[name='item_createtime_end']").val(),
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
        batchaddrvtype: function() {
            Controller.api.bindevent();
        },
        comselectpop: function() {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            //通用搜索处理
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    ctm_id: '=',
                    ctm_mobile: 'LIKE %...%',
                    ctm_name: 'LIKE %...%',
                    old_ctm_code: '=',
                    createtime: 'BETWEEN',
                });
            });
        },
        renderList: function(type) {
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
            var conTentHeight = $(window).height() - $('#ribbon').height() - $('.content').offset().top - parseInt($('.content').css('padding-top'));
            $('.contentTable').css('height', conTentHeight);
            //          $('.contentRight').css('height', conTentHeight);
            $(window).resize(function() {
                var contentTableHeight = $(window).height() - 45;
                $('.contentTable').css('height', contentTableHeight);
                var conTentHeight = $('.contentTable').height() - 45;
                var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 30;
                var tableHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 30;
                $('.fixed-table-body').css('height', tableHeight);
                $('.tdDetail').css('height', iframeHeight);
            })
            if (window.frameElement && window.frameElement.tagName == 'IFRAME') {
                $('#ribbon').css('display', 'none');
                $('body').css('background', '#fff');
            }
            // 初始化表格参数配置
            var pagination = true;
            var ctmType = type;
            listUrl = 'customer/customer/' + type;
            if (type == 'search') {
                pagination = false;
            }
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({
                extend: {
                    index_url: listUrl,
                    add_url: 'customer/customer/add',
                    edit_url: 'customer/customer/edit',
                    del_url: 'customer/customer/del',
                    multi_url: 'customer/customer/multi',
                    multi_osc_url: 'customer/customer/batchupdateosc',
                    multi_adminid_url: 'customer/customer/adminid',
                    multi_batchaddrvtype_url: 'customer/customer/batchaddrvtype',
                    multi_publicout_url: 'customer/customer/batchpublicout',
                    multi_invalidout_url: 'customer/customer/batchinvalidout',
                    multi_mergehiscustomer_url: 'customer/customer/mergehiscustomer',
                    table: 'customer',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                commonSearch: false,
                search: false,
                pagination: pagination,
                pk: 'ctm_id',
                sortName: 'ctm_id',
                height: ($(window).height() - 100),
                columns: [
                    [{
                            checkbox: true
                        }, {
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
                            field: 'ctm_id',
                            title: __('Ctm_id')
                        },
                        // {field: 'ctm_pass', title: __('Ctm_pass')},
                        {
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
                            field: 'ctm_sex',
                            title: __('Ctm_sex')
                        },
                        // {field: 'ctm_birthdate', title: __('Ctm_birthdate')},
                        // {field: 'ctm_tel', title: __('Ctm_tel')},
                        // {field: 'ctm_zip', title: __('Ctm_zip')},
                        {
                            field: 'ctm_addr',
                            title: __('Ctm_addr'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 16)
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'createtime',
                            title: __('Customer createtime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'ctm_mobile',
                            title: __('Ctm_mobile')
                        },
                        // {field: 'ctm_ifrevmail', title: __('Ctm_ifrevmail')},
                        {
                            field: 'ctm_explore',
                            title: __('Ctm_explore'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'ctm_source',
                            title: __('Ctm_source')
                        },
                        // {field: 'ctm_company', title: __('Ctm_company')},
                        {
                            field: 'ctm_job',
                            title: __('Ctm_job'),
                        }, {
                            field: 'developStaffName',
                            title: __('developStaff'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'ctm_remark',
                            title: __('Ctm_remark'),
                            // formatter: function(value, row, index) {
                            //     return Backend.api.formatter.content(value, row, index, 14)
                            // },
                            // cellStyle: function(value, row, index) {
                            //     return {
                            //         css: {
                            //             "white-space": "nowrap",
                            //         }
                            //     }
                            // }
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "text-align": "left !important",
                                        "width": "25%",
                                        "min-width": "160px",
                                        "word-break": "break-all",
                                    }
                                };
                            },
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    //                              Backend.openInRight('customer/customer/edit/ids/' + row.ctm_id, __('Customer info'));
                                    $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                                    $('.detailIframe').attr('src', '/customer/customer/edit/ids/' + row.ctm_id);
                                },
                                'click .btn-public': function(e, value, row, index) {
                                    //移出公有客户
                                    var ctm_id = row.ctm_id;
                                    layer.confirm(__('Is Out publicCustomer?'), function(index, layero) {
                                        $.ajax({
                                            url: 'customer/customer/publicCustomer',
                                            data: {
                                                customerId: ctm_id,
                                                ctmType: ctmType
                                            },
                                            dataType: 'json',
                                            success: function(res) {
                                                if (res.code) {
                                                    table.bootstrapTable('refresh');
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
                                },
                                'click .btn-invalid': function(e, value, row, index) {
                                    //移出废弃
                                    var ctm_id = row.ctm_id;
                                    layer.confirm(__('Is Out invalidcustomerOut?'), function(index, layero) {
                                        $.ajax({
                                            url: 'customer/customer/invalidcustomerOut',
                                            data: {
                                                customerId: ctm_id
                                            },
                                            dataType: 'json',
                                            success: function(res) {
                                                if (res.code) {
                                                    table.bootstrapTable('refresh');
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
                                },
                                'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                            },
                            formatter: function(value, row, index) {
                                //var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-viewsoneInfo" title="顾客信息" ><i class="fa fa-user"></i></a>';
                                var operateHtml = ' <a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a>';
                                if (ctmType == 'publist' || ctmType == 'cstpublist') {
                                    operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-danger btn-public" title="移出公有"><i class="fa fa-pencil"></i></a>';
                                }
                                if (ctmType == 'invalid') {
                                    operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-danger btn-invalid" title="移出废弃池"><i class="fa fa-pencil"></i></a>';
                                }
                                // operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a>';
                                return operateHtml;
                            }
                        }
                    ]
                ],
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    if ($('.tdDetail').length == 0) {
                        var tdDetail = "<div class='tdDetail'><iframe class='detailIframe'></iframe></div>";
                        $('.bootstrap-table').append(tdDetail);
                        $('.bootstrap-table .detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请点击 <strong style="color: #18bc9c">顾客姓名</strong> 或者 <strong style="color: #18bc9c">相关操作按钮</strong> 显示</p></center>');
                        var contentTableHeight = $(window).height() - 25;
                        $('.contentTable').css('height', contentTableHeight);
                        var iframeHeight = $(window).height() - 75;
                        var tableHeight = $(window).height() - 155;
                        $('.fixed-table-body').css('height', tableHeight);
                        $('#rightbar').css('height', iframeHeight);
                        $('.fixed-table-container').css('width', '48%').css('float', 'left');
                        $('.tdDetail').css('width', '52%').css('float', 'left');
                        $('.tdDetail').css('height', iframeHeight);
                        // var firstId = $(table).find('tr[data-index="0"]').find('td').eq(2).html();
                        // if (firstId) {
                        //     $('.fixed-table-container').css('width', '48%').css('float', 'left');
                        //     $('.tdDetail').css('width', '52%').css('float', 'left');
                        //     $('.tdDetail').css('height', iframeHeight);
                        //     $(table).find('tr[data-index="0"]').addClass('deepShow');
                        //     $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + firstId);
                        // } else {
                        //     $('.detailIframe').css('display', 'none');
                        //     $('.tdDetail').css('display', 'none')
                        // }
                    } else {
                        // var src = $('.detailIframe').attr('src');
                        // var ids = src.split('ids/')[1];
                        // var tdList = $('#table').find('tr').find('td:eq(2)');
                        // for (var i = 0; i < tdList.length; i++) {
                        //     if (tdList[i].innerHTML == ids) {
                        //         $(table).find('tr[data-index="' + i + '"]').addClass('deepShow');
                        //         $(table).find('tr[data-index="' + i + '"]').siblings().removeClass('deepShow');
                        //         $('.detailIframe').attr('src', '/customer/customer/edit/ids/' + ids);
                        //         break;
                        //     } else {
                        //         var firstId = $(table).find('tr[data-index="0"]').find('td').eq(2).html();
                        //         $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + firstId);
                        //         $(table).find('tr[data-index="0"]').addClass('deepShow');
                        //     }
                        // }
                    }
                },
                onAll: function(name, args) {
                    console.log(name, args);
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

            isLeftToolInit = false;
            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:5px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
                if (isLeftToolInit == false) {
                    if ($('.sp_container')) {
                        var selectWidth = $('.sp_container').width();
                        console.log(selectWidth);
                        $('.sp_result_area').css('width', selectWidth);
                    }
                    isLeftToolInit = true;
                }
            });
            $('.offWrap').click(function() {
                $('.commonsearch-table').toggleClass('hidden');
                $('.offWrap').toggleClass('hidden');
                if (isLeftToolInit == false) {
                    if ($('.sp_container')) {
                        var selectWidth = $('.sp_container').width();
                        $('.sp_result_area').css('width', selectWidth);
                    }
                    isLeftToolInit = true;
                }
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();

                //自动附加订单状态条件
                $('[name="order_items.item_status"]').val('');
                $('.y-consu-con').each(function() {
                    if ($(this).val() != '') {
                        $('[name="order_items.item_status"]').val(0);
                        return false;
                    }
                });

                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    ctm_id: '=',
                    ctm_type: '=',
                    ctm_explore: '=',
                    ctm_source: '=',
                    ctm_status: '=',
                    ctm_job: '=',
                    ctm_mobile: 'LIKE %...%',
                    old_ctm_code: '=',
                    ctm_name: 'LIKE %...%',
                    'customer.createtime': 'BETWEEN',
                    'customer.ctm_depositamt': 'BETWEEN',
                    arrive_status: '=',
                    'admin.dept_id': '=',
                    ctm_first_cpdt_id: '=',
                    ctm_first_dept_id: '=',
                    admin_id: '=',
                    'customer.month': '=',
                    'customer.ctm_remark': 'LIKE %...%',
                    ctm_is_cst_public: '=',
                    ctm_is_public: '=',
                    ctm_salamt: 'BETWEEN',
                    ctm_birthdate: 'BETWEEN',
                    ctm_first_recept_time: 'BETWEEN',
                    ctm_last_recept_time: 'BETWEEN',
                    ctm_last_rv_time: 'BETWEEN',
                    ctm_first_osc_admin: '=',
                    ctm_last_osc_admin: '=',
                    ctm_first_osc_cpdt_id: '=',
                    ctm_last_osc_cpdt_id: '=',
                    ctm_first_osc_dept_id: '=',
                    ctm_last_osc_dept_id: '=',
                    ctm_first_tool_id: '=',
                    potential_cpdt: '=',

                    'order_items.dept_id': '=',
                    'project.pro_cat1': '=',
                    'project.pro_cat2': '=',
                    // 'project.pro_cat3': '=',
                    'order_items.pro_id': '=',
                    'order_items.item_paytime': 'BETWEEN',
                    'order_items.item_status': '>',
                });
            });
            //修改现场客服
            $('#toolbar').on("click", ".btn-multideduct", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_osc_url + (options.extend.multi_osc_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + selectedCustomerIds, __('batch operate'));
            });
            //修改营销人员
            $('#toolbar').on("click", ".btn-adminid", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_adminid_url + (options.extend.multi_adminid_url.match(/(\?|&)+/) ? "&id=" : "?id=") + selectedCustomerIds, __('batch operate'));
            });
            //增加回访计划
            $('#toolbar').on("click", ".btn-addrvtype", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_batchaddrvtype_url + (options.extend.multi_batchaddrvtype_url.match(/(\?|&)+/) ? "&id=" : "?id=") + selectedCustomerIds, __('batch operate'));
            });
            //批量移出公有池
            $('#toolbar').on("click", ".btn-publicOut", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_publicout_url + (options.extend.multi_publicout_url.match(/(\?|&)+/) ? "&id=" : "?id=") + selectedCustomerIds + "&ctmType=" + ctmType, __('batch operate'));
            });
            //批量移出废弃池
            $('#toolbar').on("click", ".btn-invalidOut", function(e) {
                e.preventDefault();
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['ctm_id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.multi_invalidout_url + (options.extend.multi_invalidout_url.match(/(\?|&)+/) ? "&id=" : "?id=") + selectedCustomerIds, __('batch operate'));
            });
            $(".btn-MergeHisCustomer").on('click', function() {
                layer.prompt({
                    formType: 0,
                    value: '',
                    title: '输入ID时请以,分割',
                    area: ['800px', '350px'] //自定义文本域宽高
                }, function(value, index, elem) {
                    // if (!!! value.match(/([\d]+)[\D]+([\d]+)/g)) 
                    if (/^([\d]+)[\D]+([\d]+)$/.test(value)) {
                        layer.close(index);
                        Fast.api.open('customer/customer/mergehiscustomer?value=' + value, __('MergeHisCustomer'));
                    } else {
                        Toastr.error('请输入2个正确卡号');
                        return false;
                    }
                });
            });
            $('#ul-batch-operate-customer li a').on('click', function() {
                var url = $(this).data('url');
                var fieldName = $(this).data('fieldName');
                var windowName = $(this).data('windowName');
                var layerHeight = $(window).height() >= 768 ? '480px' : $(window).height() * 62.5 / 100 + 'px';

                layer.prompt({
                  formType: 2,
                  value: '',
                  maxlength: 3000,
                  title: '请输入顾客卡号，每行一个，限200个',
                  area: ['420px', layerHeight] //自定义文本域宽高
                }, function(value, index, elem){
                  // alert(value); //得到value
                  var reg = /\d+/g;
                  var ids = value.match(reg);
                  if (ids) {
                    if (ids.length > 200) {
                        layer.msg('请控制卡号数量在200以下', {icon: 2});
                    } else {
                        layer.close(index);
                        var idsStr = ids.join(',');
                        url = url + (url.indexOf('?') ? '&' : '?') + fieldName + '=' + idsStr;
                        Fast.api.open(url, windowName);
                    }
                  } else {
                     layer.msg('', {icon: 2});
                     layer.close(index);
                  }
                });
            });

            $('#search-selector-pro').selectPage({
                data: '/base/project/comselectpop',
                params: function() {
                    return {
                        "pkey_name": "pro_id",
                        "order_by": [
                            ["pro_id", "ASC"],
                        ],
                        "field": "pro_name",
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
                    // comselcallback(data);
                    $('#search-pro_id').val(data.pro_id);
                },
            });

            //导出
            $('#btn-export').on('click', function() {
                var url = '/customer/customer/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter) + '&type=' + type;
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            var type = Fast.api.query('type') ? Fast.api.query('type') : 'index';
            return Backend.api.commondownloadprocess('/customer/customer/downloadprocess?type=' + type);
        },
        mergeprocess: function() {
            $('#btn-download').css('display', 'none');
            $('#btn-regenerate').css('display', 'none');
            $('#btn-del-download').css('display', 'none');
            $('.layui-layer-btn layui-layer-footer btn').css('display', 'none');
            Backend.api.commondownloadprocess('/customer/customer/mergeprocess');
        },
        api: {
            bindevent: function() {
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

                $(document).on("change", ".c-pdc1", function() {
                    changeCate();
                })
                $(document).on("change", ".c-pdc2", function() {
                    changeCate1();
                })
                Form.api.bindevent($("form[role=form]"));
            },
        }
    };
    var yjyApi = {
        formatter: {
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
                }
                return __('osc_type_' + value);
            },
        },
    }

    function changeCate() {
        var cate = $(".c-pdc1").val();
        var tArg = arguments;
        $.ajax({
            url: "base/project/getLv2Cate",
            type: 'post',
            dataType: 'json',
            data: {
                cate_id: cate
            },
            success: function(data) {
                $('.c-pdc2').html('');
                sortData = Object.keys(data);
                sortData.sort();
                for (var i in sortData) {
                    $('.c-pdc2').append('<option value="' + sortData[i] + '">' + data[sortData[i]] + '</option>');
                }
                if (tArg.length >= 2) {
                    var initValue = tArg[0];
                    $('.c-pdc2').val(initValue);
                    changeCate1(tArg[1]);
                } else {
                    changeCate1();
                }
            }
        });
    }

    function changeCate1() {
        var cate = $('.c-pdc2').val();
        var tArg = arguments;
        $.ajax({
            url: "base/project/getLv2Cate",
            type: 'post',
            dataType: 'json',
            data: {
                cate_id: cate
            },
            success: function(data) {
                $('c-pdc3').html('');
                sortData = Object.keys(data);
                sortData.sort();
                for (var i in sortData) {
                    $('c-pdc3').append('<option value="' + sortData[i] + '">' + data[sortData[i]] + '</option>');
                }
                if (tArg.length > 0) {
                    $('c-pdc3').val(tArg[0]);
                }
            }
        });
    }

    return Controller;
});