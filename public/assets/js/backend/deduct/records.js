define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'layer', 'Viewer'], function($, undefined, Backend, Table, Form, layer, undefined) {
    var Controller = {
        index: function() {
            return Controller.renderList('DEDUCT');
        },
        listforosc: function() {
            return Controller.renderList('OSC');
        },
        downloadprocess: function() {
            let type = Fast.api.query('type', window.location.href);
            return Backend.api.commondownloadprocess('deduct/records/downloadprocess?type=' + type);
        },
        undeliveriedlist: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'deduct/records/undeliveriedlist',
                    table: 'deduct_records',
                }
            });
            var table = $("#table");
            var hOrderId = $('#h-order-id').val();
            var url = $.fn.bootstrapTable.defaults.extend.index_url;
            if (hOrderId) {
                url += (url.indexOf("?") > -1 ? "&" : "?") + 'ids=' + hOrderId;
            }
            // 初始化表格
            table.bootstrapTable({
                url: url,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        // {checkbox: true},
                        {
                            field: 'id',
                            title: __('Id')
                        },
                        // {field: 'order_item_id', title: __('Order_item_id')},
                        {
                            field: 'item_name',
                            title: __('Order_item_id'),
                            formatter: function(value) {
                                return Backend.api.formatter.content(value, '', '', 12);
                            }
                        }, {
                            field: 'deduct_times',
                            title: __('Deduct_times')
                        }, {
                            field: 'deduct_amount',
                            title: __('Deduct amount')
                        }, {
                            field: 'deduct_benefit_amount',
                            title: __('Deduct benefit amount')
                        }, {
                            field: 'status',
                            title: __('Status'),
                            formatter: function(value, row, index) {
                                return __('deduct_status_' + value);
                            }
                        }, {
                            field: 'admin_nickname',
                            title: __('Admin_id')
                        },
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {
                            field: 'updatetime',
                            title: __('Updatetime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-delivery': function(e, value, row, index) {
                                    //写完请删除此段注释
                                    //row.id 划扣记录ID \app\admin\model\DeductRecords::STATUS_COMPLETED
                                    //row.order_item_id 订单项ID
                                    //***** row.pro_id 为实际产品ID *****
                                    //***** row.deduct_times 为划扣次数，产品个数 *****
                                    //下面的内容请自行调整
                                    e.stopPropagation;
                                    var baseUrl = '';
                                    params = 'ids=' + row.pro_id + '&qty=' + row.deduct_times;
                                    var url = baseUrl + (baseUrl.match(/(\?|&)+/) ? "&" + params : "?" + params);
                                    Fast.api.open(params, __('Edit'));
                                    return false;
                                }
                            },
                            formatter: function(value, row, index) {
                                return '<a href="javascript:;" class="btn btn-xs btn-success btn-delivery" title="' + __('Delivery') + '">' + '<i class="fa fa-mail-forward"></i>' + '</a>';
                            }
                        },
                    ]
                ],
                onLoadSuccess: function() {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        renderList: function(type) {
            //          $(window).resize(function(){
            //              var winHeight = $(window).height()-43;
            //              var conHeight = $('.contentLeft').height();
            //              if(winHeight>conHeight){
            //              $('#showIframe').css('height',winHeight)
            //              }else{$('#showIframe').css('height',conHeight)}
            //          })
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
            let indexUrl = 'deduct/records/index';
            if (type == 'OSC') {
                indexUrl = 'deduct/records/listforosc';
            }
            Table.api.init({
                extend: {
                    index_url: indexUrl,
                    add_url: 'deduct/records/add',
                    // edit_url: 'deduct/records/edit',
                    del_url: 'deduct/records/reverse',
                    multi_url: 'deduct/records/batchreverse',
                    table: 'deduct_records',
                }
            });
            var table = $("#table");
            var hOrderItemId = $('#h-order-item-id').val();
            var currentOp = '';
            var currentFilter = '';
            var url = $.fn.bootstrapTable.defaults.extend.index_url;
            if (hOrderItemId) {
                url += (url.indexOf("?") > -1 ? "&" : "?") + 'order_item_id=' + hOrderItemId;
            }
            var yjyArea = [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 600 ? '600px' : '95%'];
            var yjyLayerOptions = {
                area: yjyArea,
                title: __('Staff benefit detail'),
                shadeClose: true,
                shade: false,
                maxmin: true,
                moveOut: true,
                // content: url,
                zIndex: Layer.zIndex,
            }
            var forbiddenRecIds = new Array();
            let columnsPart1 = [{
                    checkbox: true
                }, {
                    field: 'id',
                    title: __('No.'),
                    formatter: function(value, row, index) {
                        return index + 1;
                    }
                },
                // {field: 'id', title: __('Id')},
                {
                    field: 'ctm_name',
                    title: __('customer'),
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
                            Fast.api.open('/cash/order/deductview/ids/' + row['ctm_id'], '顾客信息')
                        }
                    },
                }, {
                    field: 'ctm_id',
                    title: __('ctm_id')
                }, {
                    field: 'ctm_explore',
                    title: __('ctm_explore')
                }, {
                    field: 'ctm_source',
                    title: __('ctm_source')
                },
                // {field: 'order_item_id', title: __('Order_item_id')},
                {
                    field: 'item_name',
                    title: __('Order_item_id'),
                    formatter: function(value) {
                        return Backend.api.formatter.content(value, '', '', 12);
                    },
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'item_spec',
                    title: __('item_spec'),
                    formatter: function(value) {
                        return Backend.api.formatter.content(value, '', '', 12);
                    },
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, 
                {
                    field: 'pro_cat1',
                    title: __('Pro_cat1')
                    
                }, 
                {
                    field: 'pro_cat2',
                    title: __('Pro_cat2')
                   
                }, 
                  {
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
                    field: 'deduct_times',
                    title: __('Deduct_times')
                }, {
                    field: 'deduct_amount',
                    title: '<span>' + __('Deduct amount') + '</span>'
                }, {
                    field: 'deduct_benefit_amount',
                    title: '<span title="' + __('dealed by rate') + '">' + __('Deduct benefit amount') + "</span>"
                }, {
                    field: 'status',
                    title: __('Status'),
                    formatter: function(value, row, index) {
                        return __('deduct_status_' + value);
                    },
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, 
              
                {
                    field: 'dept_name',
                    title: __('Deduct dept'),
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'consult_admin_name',
                    title: '<span class="text-success" title="开单时的网络客服" data-toggle="tooltip">网络客服<i class="fa fa-question-circle-o"></i></span>',
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'osconsult_admin_name',
                    title: __('Osconsult staff'),
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'recept_admin_name',
                    title: __('Recept staff'),
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'prescriber_name',
                    title: __('prescriber_name'),
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'admin_nickname',
                    title: __('Admin_id'),
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                },
            ];
            let columnsPart3 = [
                // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                {
                    field: 'item_paytime',
                    title: __('item_paytime'),
                    formatter: Table.api.formatter.datetime,
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'createtime',
                    title: __('Deduct time'),
                    formatter: Table.api.formatter.datetime,
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                'white-space': 'nowrap',
                            }
                        }
                    },
                }, {
                    field: 'operate',
                    title: __('Operate'),
                    table: table,
                    events: {
                        'click .btn-viewsone': function(e, value, row, index) {
                            yjyLayerOptions.content = $('#h-staffs-' + row['id']).html();
                            yjyLayerOptions.title = __('Staff benefit detail') + '(ID: ' + row.id + ')';
                            Layer.open(yjyLayerOptions);
                        },
                        'click .btn-img': function(e, value, row, index) {
                            var deductRecordsId = row.id;
                            var loadLIndex = layer.load();
                            $.ajax({
                                url: 'deduct/records/deductImg',
                                data: {
                                    deductRecordsId: deductRecordsId
                                },
                                dataType: 'json',
                                success: function(url) {
                                    filePath = url;
                                    if (filePath) {
                                        var data = [];
                                        $('#h-deduct-img-area').empty();
                                        var picCnt = 0;
                                        for (var i in filePath) {
                                            picCnt ++;
                                            $('#h-deduct-img-area').append($(`<img src="/general/attachment/displayimg?filePath=${filePath[i]}" />`));
                                        }
                                        layer.close(loadLIndex);

                                        // {hidden: function() {}
                                        // var viewer = $('#h-deduct-img-area').viewer();
                                        if (picCnt == 0) {
                                            layer.msg('无图', {icon: 2});
                                            return;
                                        }
                                        try {
                                            var viewer = new Viewer($('#h-deduct-img-area')[0], {
                                                hidden: function() {
                                                    viewer.destroy();
                                                }
                                            });
                                            viewer.show();
                                        } catch (e) {
                                            console.log(e);
                                            layer.msg('出了点小问题', {icon: 2});
                                        }
                                    } else {
                                        layer.close(loadLIndex);
                                        layer.msg('没有票据', {
                                            icon: 2
                                        });
                                    }
                                },
                                error: function(err) {
                                    layer.close(loadLIndex);
                                }
                            })
                        },
                        'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                    },
                    cellStyle: function(value, row, index) {
                        return {
                            css: {
                                "white-space": "nowrap"
                            }
                        };
                    },
                    formatter: function(value, row, index) {
                        var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-warning btn-img" title="参考图" ><i class="fa fa-image"></i></a>';
                        operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-success btn-viewsone" title="查看明细"><i class="fa fa-search"></i></a>';
                        //后台代码更改时，此处需修改--已出库
                        //物资/药品 未出库，显示反划扣
                        if (row.item_type != 9) {
                            if (row.status == 1) {
                                operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a>';
                            } else {
                                forbiddenRecIds.push(index);
                            }
                        } else {
                            //项目出库，显示反划扣
                            if (row.status == 2) {
                                operateHtml += ' <a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a>';
                            } else {
                                forbiddenRecIds.push(index);
                            }
                        }
                        var deductAmount = 0;
                        operateHtml += '<div class="hidden" id="h-staffs-' + row['id'] + '">' + '<div>' + __('Deduct amount') + ': ' + row.deduct_amount + '<br />' + __('Deduct Benefit amount') + ': ' + row.deduct_benefit_amount + '<br />' + '</div>' + '<table class="table table-bordered">' + '<tr>' + '<th>' + __('Staff name') + '</th>' + '<th>' + __('percent') + '</th>' + '<th>' + __('final_percent') + '</th>' + '<th>' + __('final_amount') + '</th>' + '<th>' + __('final_benefit_amount') + '</th>' + '</tr>';
                        if (row['staff_records']) {
                            for (var roleId in row['staff_records']) {
                                var roleInfo = row['staff_records'][roleId];
                                operateHtml += '<tr><td colspan="4">' + Backend.api.formatter.content(roleInfo['role_name'], '', '', 10) + ' : ' + roleInfo['role_percent'] + '%</td></tr>';
                                for (var i in roleInfo['role_staffs']) {
                                    var staffInfo = roleInfo['role_staffs'][i];
                                    operateHtml += '<tr>' + '<td>' + staffInfo['admin_name'] + '</td>' + '<td>' + staffInfo['percent'] + '</td>' + '<td>' + staffInfo['final_percent'] + '</td>' + '<td>' + staffInfo['final_amount'] + '</td>' + '<td>' + staffInfo['final_benefit_amount'] + '</td>' + '</tr>';
                                }
                            }
                        }
                        operateHtml += '</table></div>';
                        return operateHtml;
                    }
                },
            ];
            columns = [...columnsPart1, ...yjyDeductRoleSets, ...columnsPart3];
            // 初始化表格
            table.bootstrapTable({
                url: url,
                pk: 'id',
                sortName: 'id',
                escape: false,
                search: false,
                commonSearch: false,
                height: ($(window).height() - 60),
                columns: [
                    columns,
                ],
                onLoadSuccess: function(data) {
                    $("[data-toggle='tooltip']").tooltip();
                    if (forbiddenRecIds.length > 0) {
                        var checkboxes = table.find('.bs-checkbox>input');
                        for (var i in forbiddenRecIds) {
                            if (checkboxes.eq(forbiddenRecIds[i])) {
                                checkboxes.eq(forbiddenRecIds[i]).prop('disabled', true);
                            }
                        }
                    }
                    forbiddenRecIds = new Array();
                    if (data.summary) {
                        $('#sum_ded_times').text(data.summary.deduct_times ? data.summary.deduct_times : 0);
                        $('#sum_ded_total').text(data.summary.deduct_total ? data.summary.deduct_total : 0);
                        $('#sum_ded_benefit_total').text(data.summary.deduct_benefit_total ? data.summary.deduct_benefit_total : 0);
                    }
                },
                onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
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
            $('.btn-default').on('click', function() {
                $('[name="project.pro_cat2"]').html('');
                $('[name="project.pro_cat2"]').append('<option value=""></option>');
            })
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
            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    deduct_amount: 'BETWEEN',
                    deduct_benefit_amount: 'BETWEEN',
                    'deduct_records.createtime': 'BETWEEN',
                    // 'deduct_records.deduct_times': 'BETWEEN',
                    'deduct_records.admin_id': '=',
                    'order_items.dept_id': '=',
                    'deduct_records.status': '=',
                    'order_items.item_paytime': 'BETWEEN',
                    'order_items.admin_id': '=',
                    'order_items.customer_id': '=',
                    'order_items.pro_name': 'LIKE %...%',
                    'order_items.pro_spec': 'LIKE %...%',
                    'order_items.item_used_times': 'BETWEEN',
                    'customer.ctm_name': 'LIKE %...%',
                    'admin.dept_id': '=',
                    'customer.old_ctm_code': '=',
                    'customer.ctm_explore': '=',
                    'customer.ctm_source': '=',
                    'customer.ctm_first_tool_id': '=',
                    'coc.dept_id': '=',
                    'coc.osc_type': '=',
                    'project.pro_cat1': '=',
                    'project.pro_cat2': '=',
                });
            });
            $('.btn-batchreverse').on('click', function() {
                var recIds = new Array();
                var selections = table.bootstrapTable('getSelections');
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    recIds.push(selections[curIndex]['id']);
                }
                if (recIds.length <= 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                } else {
                    Layer.confirm(__('Are sure to reverse deductions!'), function(index, layero) {
                        var idsParam = recIds.join(',');
                        var options = table.bootstrapTable('getOptions');
                        var url = options.extend.multi_url + (options.extend.multi_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + idsParam;
                        $.ajax({
                            url: url,
                            type: 'post',
                            dataType: 'json',
                            success: function(res) {
                                console.log(index, layero);
                                Layer.close(index);
                                if (res.error) {
                                    var msg = res.msg ? res.msg : __('Operation failed');
                                    Toastr.error(msg);
                                } else {
                                    var msg = res.msg ? res.msg : __('Operation completed');
                                    Toastr.success(msg);
                                    $(".btn-refresh").trigger("click");
                                }
                            },
                            error: function(e) {
                                console.log(e);
                            },
                        });
                    });
                }
            });
            if ($('#btn-export').length) {
                $('#btn-export').on('click', function() {
                    var url = '/deduct/records/downloadprocess' + '?type=' + type + '&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                    Fast.api.open(url, __('Downloading page'));
                });
            }
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    var yjyApi = {
        formatter: {
            oscType: function(value) {
                var value = parseInt(value);
                if (isNaN(value) || value == 0) {
                    return '-';
                } else {
                    if (value == 1) {
                        return '<span class="text-success">' + __('osc_type_' + value) + '</span>';
                    }
                }
                return __('osc_type_' + value);
            },
        },
    }
    return Controller;
});