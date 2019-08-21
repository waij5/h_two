define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/rvinfo/index',
                    // add_url: 'customer/rvinfo/presearch',
                    add_url: 'customer/rvinfo/add',
                    edit_url: 'customer/rvinfo/edit',
                    del_url: 'customer/rvinfo/del',
                    //multi_url: 'customer/rvinfo/multi',
                    table: 'rvinfo',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rvi_id',
                sortName: 'rvi_id',
                sortOrder: 'DESC',
                commonSearch: false,
                search: false,
                columns: [
                    [{
                            checkbox: true
                        }, {
                            field: 'rvi_id',
                            title: __('Rvi_id')
                        }, {
                            field: 'rvi_tel',
                            title: __('Rvi_tel')
                        },
                        // {field: 'customer_id', title: __('Customer_id')},
                        {
                            field: 'ctm_name',
                            title: __('Customer_id')
                        }, {
                            field: 'rvt_type',
                            title: __('Rvt_type')
                        }, {
                            field: 'fat_name',
                            title: __('Fat_id')
                        }, {
                            field: 'rvi_content',
                            title: __('Rvi_content'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 12);
                            },
                        },
                        // {field: 'Admin_id', title: __('Admin_id')},
                        {
                            field: 'nickname',
                            title: __('Admin_id')
                        }, {
                            field: 'createtime',
                            title: __('Createtime'),
                            formatter: Table.api.formatter.datetime
                        },
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            formatter: function(value, row, index, custom) {
                                var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-viewsone" title="顾客信息" data-id='+row['customer_id']+'><i class="fa fa-user"></i></a>';
                                var timeStamp = new Date(new Date().setHours(0, 0, 0, 0)) / 1000;
                                var timeStamp2 = timeStamp+86399;
                                //当天的回访记录可以修改
                                if (row['rv_time'] &&  (row['rv_time'] < timeStamp || row['rv_time'] > timeStamp2)) {
                                // if (row['rv_time']) {
                                    return operateHtml; 
                                }
                                operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a>';
                                return operateHtml; 
                            },
                            events: {
                                'click .btn-viewsone': function (e, value, row, index) {
                                    Fast.api.open('cash/order/deductview/ids/' + row['customer_id'],'顾客信息','op','sideShow');
                                },
                                                                 'click .btn-editone': function (e, value, row, index) {
//                                  console.log(row)
                                    Fast.api.open('customer/rvinfo/edit/ids/' + row.rvi_id,__('Edit'),'op','sideShow');
                                 }
                            },
                             
                        }
                    ]
                ],
                onLoadSuccess: function(data) {
                    $(table).find("[data-toggle='tooltip']").tooltip();
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
                if($('.layui-layer')){$('.layui-layer').css('top',$(table).offset().top);}
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'a.customer_id': '=',
                    'a.createtime': 'BETWEEN',
                    'a.rv_time': 'BETWEEN',
                    'a.fat_id': '=',
                    'a.admin_id': '=',
                    'a.rvi_content': 'LIKE %...%',
                    'a.rvt_type': '=',
                    'b.ctm_id': '=',
                    'b.ctm_name': 'LIKE %...%',
                    'b.ctm_mobile': 'LIKE %...%',
                });
            });
        },
        todayrevisitnotices: function() {
            if (window.frameElement && window.frameElement.tagName == 'IFRAME') {
                $('#ribbon').css('display', 'none');
                $('body').css('background', '#fff');
            }
            
            Table.api.init({
                extend: {
                    index_url: 'customer/rvinfo/todayrevisitnotices',
                    // edit_url: 'customer/rvinfo/edit',
                    table: 'rvinfo',
                }
            });
            var table = $("#table");
            var fatList = new Array();
            var isXSuper = (top.$('#h-yjy-x-super').length && top.$('#h-yjy-x-super').val()) ? 1 : 0;
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'rvi_id',
                sortName: 'rv_date desc, rvi_id',
                sortOrder: 'DESC',
                commonSearch: false,
                search: false,
                onLoadSuccess: function(data) {
                    if (fatList.length != data.fatList) {
                        fatList = data.fatList;
                        var fats = table.find('tbody tr .cls-fat');
                        var fatSelect = '<select class="form-control cls-fat-select hidden" style="width: 136px;"><option>--</option>';
                        for (var i in fatList) {
                            fatSelect += '<option value="' + i + '">' + fatList[i]['fat_name'] + '</option>';
                        }
                        fatSelect += '</select>';

                        for (var i = 0; i < data.rows.length; i ++) {
                            fats.eq(i).append(fatSelect);
                            fats.eq(i).find('select').val(data.rows[i]['fat_id']);
                        }
                    }
                },
                columns: [
                    [
                        {
                            field: 'customer_id',
                            title: __('No.'),
                            formatter: function(value, row, index) {
                                return index + 1;
                            }
                        },
                        {
                            field: 'customer_id',
                            title: __('Ctm_id')
                        },
                        {
                            field: 'ctm_name',
                            title: __('Customer_id')
                        },
                        {
                            field: 'nickname',
                            title: __('Rv_admin_id'),
                            formatter: function (value, row, index) {
                                contacts = new Array();
                                if (row.ctm_mobile) {
                                    contacts.push('<i class="glyphicon glyphicon-phone text-success">' + row.ctm_mobile + '</i>');
                                }
                                if (row.ctm_tel) {
                                    contacts.push('<i class="glyphicon glyphicon-earphone text-success">' + row.ctm_tel + '</i>');
                                }

                                return '<span class="cls-rv-admin">' + value + '</span><span class="cls-contacts hidden">' + contacts.join('<br />') + '</span>';
                            },
                        },
                        {
                            field: 'rvt_type',
                            title: __('Rvt_type')
                        },
                        {
                            field: 'rv_plan',
                            title: '时间/计划',
                            formatter: function(value, row) {
                                if (row.rv_time) {
                                    var str = '<span class="text-success">' + Backend.api.formatter.datetime(row.rv_time) + '(实)</span><br />';
                                } else {
                                    var str = '<span class="text-danger">未回访</span><br />';
                                }
                                str += value;
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        'text-align': 'left !important',
                                    },
                                    classes: 'cls-rvplan-rvtime',
                                };
                            },
                        },
                        {
                            field: 'rvi_content',
                            title: __('Rvi_content'), 
                            formatter: function(value, row, index) {
                                value = value ? value : '';
                                var str = '<div contenteditable="false" class="modifyContent">' + value + '</div>';
                                return str;
                            },
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
                        },
                        {
                            field: 'fat_name',
                            title: __('Rv_fat_id'),
                            class: 'cls-fat',
                            formatter: function(value, row, index) {
                                if (typeof value == 'undefined' || value == null) {
                                    value = '';
                                }                              
                                var str = '<span style="color:#000;word-brea:break-all;text-align:left;width:100%" class="btn-editTdReason" title="点击修改">' + value + '</span>';
                                str += '<textarea class="tdModifyTextarea modifyReason hidden" value="' + value + '" style="width:145px;height:80px;resize:none">' + value + '</textarea>';
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            },
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            formatter: function(value, row, index, custom) {
                                var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-viewsone" title="用户信息" data-id='+row['customer_id']+'><i class="fa fa-user"></i></a> ';
                               
                                if (row.canEdit) {
                                    operateHtml = '<a href="javascript:;" class="btn btn-xs btn-default btn-editone" title="' + __('Edit') + '"><i class="fa fa-pencil"></i></a> <a href="javascript:;" class="btn btn-xs btn-success btn-saveone hidden" title="' + __('Save') + '"><i class="fa fa-check"></i></a> ' + operateHtml;
                                }
                                return operateHtml;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            },
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    // ?mode=simple&
                                    // Fast.api.open('customer/rvinfo/edit?ids=' + row.rvi_id, __('Edit'));
                                    var currentTr = $(this).parents('tr');
                                    currentTr.addClass('deepShow');

                                    currentTr.find('.btn-editone').addClass('hidden');
                                    currentTr.find('.btn-saveone').removeClass('hidden');
                                    currentTr.find('.cls-fat span').addClass('hidden');
                                    currentTr.find('.cls-fat select').removeClass('hidden');
                                    currentTr.find('.cls-rv-admin').addClass('hidden');
                                    currentTr.find('.cls-contacts').removeClass('hidden');
                                    currentTr.find('.modifyContent').attr('contenteditable', true);
                                },
                                'click .btn-saveone': function(e, value, row, index) {
                                    var lIndex = layer.load(3);
                                    var currentTr = $(e.currentTarget).parents('tr');

                                    var rviContent = $.trim(currentTr.find('.modifyContent').text());
                                    if (rviContent.length == 0) {
                                        layer.close(lIndex);
                                        layer.msg(__('请填写回访情况'), {icon: 2});
                                        return false;
                                    } else {
                                        if (rviContent.length >= 255) {
                                            layer.close(lIndex);
                                            layer.msg(__('请保证内容在255个字符以内'), {
                                                icon: 2
                                            });
                                            return false;
                                        }
                                    }

                                    $.post({
                                        url: '/customer/rvinfo/quickEdit',
                                        data: {ids: row.rvi_id, rvi_content: rviContent, fat_id: currentTr.find('.cls-fat-select').val()},
                                        dataType: 'json',
                                        async: false,
                                        success: function(res) {
                                            var icon = 2;
                                            if (res.code) {
                                                var updatedRvinfo = res.data.rvinfo;
                                                currentTr.removeClass('deepShow');

                                                if (res.data.canEdit) {
                                                    currentTr.find('.btn-editone').removeClass('hidden');
                                                    currentTr.find('.btn-saveone').addClass('hidden');
                                                } else {
                                                    currentTr.find('.btn-editone').remove();
                                                    currentTr.find('.btn-saveone').remove();
                                                }
                                                var updatedFat = fatList[updatedRvinfo.fat_id] ? fatList[updatedRvinfo.fat_id]['fat_name'] : '';
                                                currentTr.find('.cls-fat span').text(updatedFat);
                                                // currentTr.find('.cls-fat-rvtime').html(Backend.api.formatter.datetime(updatedRvinfo.rv_time));
                                                currentTr.find('.cls-fat span').removeClass('hidden');
                                                currentTr.find('.cls-fat select').addClass('hidden');
                                                currentTr.find('.cls-rv-admin').removeClass('hidden');
                                                currentTr.find('.cls-contacts').addClass('hidden');
                                                currentTr.find('.modifyContent').attr('title', updatedRvinfo.rvi_content);
                                                // currentTr.find('.modifyContent').css('border-width', 0);
                                                // currentTr.find('.modifyContent').prop('readonly', true);
                                                currentTr.find('.modifyContent').attr('contenteditable', false);
                                                currentTr.find('.cls-rvplan-rvtime').html('<span class="text-success">' + Backend.api.formatter.datetime(updatedRvinfo.rv_time) + '(实)</span><br />' + updatedRvinfo.rv_plan);
                                              
                                                icon = 1;
                                            }

                                            layer.msg(res.msg, {icon: icon});
                                        },
                                        error: function(e, xhr) {
                                            layer.msg(__('Operation failed'), {icon: 2});
                                        }
                                    })
                                    layer.close(lIndex);
                                    
                                },
                                'click .btn-viewsone': function(e, value, row, index) {
                                    parent.Fast.api.open('cash/order/deductview/ids/' + row['customer_id'],'顾客信息');
                                }
                            }
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            // 为表格绑定事件
            // $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            // $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
            //     $('.commonsearch-table').toggleClass('hidden');
            // });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'notOnlyUseToday': '=',
                    'rvinfo.admin_id': '=',
                    'rvinfo.fat_id': '=',
                    'rvinfo.rv_date': 'BETWEEN',
                    'rvinfo.rvi_content': 'LIKE %...%',
                    'rvinfo.rv_time': 'BETWEEN',
                    'admin.dept_id': '=',
                    'customer.ctm_id': '=',
                    'customer.arrive_status': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_first_cpdt_id': '=',
                    'onlyNoneRevisit': '=',
                    'customer.ctm_rank_points': 'BETWEEN',
                    'customer.ctm_pay_points': 'BETWEEN',
                    'customer.ctm_salamt': 'BETWEEN',
                    'customer.ctm_depositamt': 'BETWEEN',
                    'rvinfo.rvt_type': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_source': '=',
                    'customer.ctm_explore': '=',
                });
            });
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

             //提交后自动刷新页面
            $('#btn-refresh-rvinfo').on('click', function() {
                $('#rvinfoHistory-table').bootstrapTable('refresh');
            });
            $('#btn-refresh-order').on('click', function() {
                $('#orderHistory-table').bootstrapTable('refresh');
            });

            var mode = Fast.api.query('mode', window.location.href);
            if (typeof mode == 'undefined' || mode != 'simple') {

                //rvinfo按钮点击添加 
                document.getElementById("addRvinfoHistory").onclick=function(e){                
                    var ctm_id = $(this).attr('value');
                    Fast.api.open("customer/rvinfo/add?ctm_id="+ctm_id, __('Add'));

                };

                //添加回访计划
                var customerId = $('#add_rvinfo_by_plan').data('customer_id');
                $('#add_rvinfo_by_plan').on('click', function() {
                    $.ajax({
                        url: 'customer/rvinfo/addplaninfos',
                        data: {planId: $('#h_rvinfo_by_plan').val(), customerId: customerId},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code) {
                                $('#rvinfoHistory-table').bootstrapTable('refresh');
                                Toastr.success(__('Operation completed'));
                            } else {
                                Toastr.success(__('Operation failed'));
                            }
                        }

                    })
                })
                document.getElementById("add_rvtype2").onclick = function(e) {
                    var ctm_id = $(this).attr('value');
                    Fast.api.open("customer/customerosconsult/addrvtype?ctm_id=" + ctm_id, __('Add'));
                };
            }
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                $('button[type="reset"]').click(function(){
                $('.bootstrap-select').each(function(index) {
                var searchId = $(this).find('.selectpicker').attr('name');
                var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
                $(this).find('.dropdown-toggle').attr('title',defaultVal).attr('data-id',searchId).removeClass('bs-placeholder');
                $(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
                $(this).find('.inner').find('li').eq(0).addClass('selected active');
                $(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
                $(this).find('.inner').find('li').removeClass('hidden');
                })
            })
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
                // $('#sel-rv-status').on('change', function() {
                //     console.log($(this).val());
                //     if ($(this).val() == 1 || $(this).val() == '') {
                //         $('#div-rv-fat').addClass('hidden');
                //         // $('[name="row[fat_id]"]').val('');
                //         $('[name="row[fat_id]"]').selectpicker('val', '');
                //     } else {
                //         $('#div-rv-fat').removeClass('hidden');
                //     }
                // })
            }
        }
    };
    return Controller;
});