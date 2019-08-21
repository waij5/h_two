define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'selectpage'], function($, undefined, Backend, Table, Form, selectpage) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/prosets/index',
                    add_url: 'base/prosets/add',
                    edit_url: 'base/prosets/edit',
                    del_url: 'base/prosets/del',
                    multi_url: 'base/prosets/multi',
                    table: 'pro_sets',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'set_id',
                sortName: 'set_id',
                search: false,
                commonSearch: false,
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'set_id',
                        title: __('Set_id')
                    }, {
                        field: 'set_name',
                        title: __('Set_name'),
                        formatter: Backend.api.formatter.content
                    }, {
                        field: 'set_type',
                        title: __('Set_type'),
                        formatter: function(value, row, index) {
                            return __('Pro_type_' + value);
                        }
                    }, {
                        field: 'set_status',
                        title: __('Set_status'),
                        formatter: Backend.api.formatter.status
                    }, {
                        field: 'set_remark',
                        title: __('Set_remark'),
                        formatter: Backend.api.formatter.content
                    }, {
                        field: 'operate',
                        title: __('Operate'),
                        table: table,
                        events: Table.api.events.operate,
                        formatter: Table.api.formatter.operate
                    }]
                ],
                onLoadSuccess: function() {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    set_id: '=',
                    set_name: 'LIKE %...%',
                    set_type: '=',
                    set_status: '=',
                });
            });
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
        	
            var setId = $('#setId').val();
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    // index_url: 'base/rvplan/index',
                    // add_url: 'base/prosetitems/add/setId/' + setId,
                    edit_url: 'base/prosetitems/edit',
                    index_url: 'base/prosetitems/setitemlist/setId/' + setId,
                    del_url: 'base/prosetitems/del',
                    // multi_url: 'base/prosetitems/multi',
                    table: 'rvplan',
                }
            });
            var table = $("#table");
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'set_item_id',
                sortName: 'set_item_id',
                sortOrder: 'ASC',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {
                            field: 'pro_name',
                            title: __('Pro_name')
                        }, {
                            field: 'pro_amount',
                            title: __('Pro_amount')
                        }, {
                            field: 'set_item_amount',
                            title: __('Set_item_amount'),
                            formatter: function(value, row, index) {
                                return '<input type="number" id="y_item_amount_' + index + '" value="' + value + '"' + ' class="form-control" />';
                            }
                        }, {
                            field: 'set_item_qty',
                            title: __('Set_item_qty'),
                            formatter: function(value, row, index) {
                                return '<input type="number" id="y_item_qty_' + index + '" value="' + value + '"' + ' class="form-control" />';
                            }
                        }, {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                                'click .btn-editone': function(e, value, row, index) {
                                    var setItemAmout = $('#y_item_amount_' + index).val();
                                    var setItemQty = $('#y_item_qty_' + index).val();
                                    if (setItemAmout == '' || (setItemAmout = parseFloat(setItemAmout)) < 0) {
                                        Layer.msg(__('Invalid amount'), {
                                            icon: 2
                                        });
                                        return false;
                                    }
                                    if (setItemQty <= 0) {
                                        Layer.msg(__('Invalid qty'), {
                                            icon: 2
                                        });
                                        return false;
                                    }
                                    var loadIndex = layer.load();
                                    $.post({
                                        url: 'base/prosetitems/edit/ids/' + row.set_item_id,
                                        dataType: 'json',
                                        data: {
                                            set_item_amount: setItemAmout,
                                            set_item_qty: setItemQty,
                                        },
                                        success: function(data) {
                                            Layer.close(loadIndex);
                                            if (data.error) {
                                                Toastr.error(data.msg);
                                            } else {
                                                Toastr.success(data.msg);
                                                $('.btn-refresh').trigger('click');
                                            }
                                        },
                                        error() {
                                            Layer.close(loadIndex);
                                        },
                                    });
                                }
                            },
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                onLoadSuccess: function() {
                    $("[data-toggle='tooltip']").tooltip();
                   
                }
            });
            Controller.api.bindevent();
            // 为表格绑定事件
            Table.api.bindevent(table);
            var params = '?mode=single';
            $('#a-search-project').on('click', function() {
                Fast.api.open('base/project/comselectpop' + params, __('Select project'));
            });
            $('#a-search-product_1').on('click', function() {
                Fast.api.open('store/goods/comselectpop' + params + '&type=1', __('Select medicine'));
            });
            $('#a-search-product_2').on('click', function() {
                Fast.api.open('store/goods/comselectpop' + params + '&type=2', __('Select product'));
            });
            $('.field_pro_amount').on('change', function() {
                $('#set_item_amount').val($('.field_pro_amount').val());
            })
            $('#btn-add-item').on('click', function() {
                var proId = $('#selector-pro').val();
                var setItemAmout = $.trim($('#set_item_amount').val());
                var setItemQty = $('#set_item_qty').val();
                if (proId == '') {
                    Layer.msg(__('Please select item to add'), {
                        icon: 2
                    });
                    return false;
                }
                if (setItemAmout == '' || (setItemAmout = parseFloat(setItemAmout)) < 0) {
                    Layer.msg(__('Invalid amount'), {
                        icon: 2
                    });
                    return false;
                }
                if (setItemQty <= 0) {
                    Layer.msg(__('Invalid qty'), {
                        icon: 2
                    });
                    return false;
                }
                var loadIndex = layer.load();
                $.post({
                    url: 'base/prosetitems/add',
                    dataType: 'json',
                    data: {
                        pro_set_id: setId,
                        set_pro_id: proId,
                        set_item_amount: setItemAmout,
                        set_item_qty: setItemQty,
                    },
                    success: function(data) {
                        Layer.close(loadIndex);
                        if (data.error) {
                            Toastr.error(data.msg);
                        } else {
                            Toastr.success(data.msg);
                            $('.btn-refresh').trigger('click');
                        }
                    }
                })
            })
        },
        comselectpop: function() {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            Controller.api.bindevent();
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    set_id: '=',
                    set_name: 'LIKE %...%',
                    set_type: '=',
                    set_status: '=',
                });
            });
        },
        api: {
            bindevent: function() {
                $('#set_status-switch').bootstrapSwitch({
                    onText: "正常",
                    offText: "禁用",
                    onColor: "success",
                    offColor: "danger",
                    size: "small",
                    //初始开关状态
                    state: $('#c-set_status').val() == 1 ? true : false,
                    onSwitchChange: function(event, state) {
                        if (state == true) {
                            $('#c-set_status').val(1);
                        } else {
                            $('#c-set_status').val(0);
                        }
                    }
                });
                var setType = $('#h-pro_type').val();
                $('#selector-pro').selectPage({
                    data: "/base/project/comselectpop",
                    params: function() {
                        return {
                            "pkey_name": "pro_id",
                            "order_by": [
                                ["pro_id", "ASC"],
                            ],
                            "field": "pro_name",
                            "yjyCustom[pro_type]": setType,
                            "yjyCustom[pro_status]": 1,
                        };
                    },
                    pageSize: 10,
                    showField: "pro_name",
                    searchField: "pro_name,pro_spell",
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
                    	$('#field_price').val(data.pro_amount);
                    	$('#set_item_amount').val(data.pro_amount);
                    },
                });
                 var selectWidth = $('.sp_container').width();
        			$('.sp_result_area').css('width',selectWidth);
                Form.api.bindevent($("form[role=form]"));
            },
            formatPro: function(data) {
                return data.pro_name + " | " + data.pro_amount;
            },
        }
    };
    return Controller;
});