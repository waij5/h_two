define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/couponrecord/index',
                    add_url: 'customer/couponrecord/add',
                    // edit_url: 'customer/couponrecord/edit',
                    del_url: 'customer/couponrecord/del',
                    multi_url: 'customer/couponrecord/multi',
                    table: 'coupon_records',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'customer_id', title: __('Customer_id')},
                        {field: 'ctm_name', title: __('customer_id')},
                        // {field: 'coupon_id', title: __('Coupon_id')},
                        {field: 'name', title: __('coupon_id')},
                        {field: 'balance_id', title: __('Balance_id')},
                        {field: 'used_balance_id', title: __('Used_balance_id')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'remark', title: __('Remark'), formatter: Backend.api.formatter.content},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                onLoadSuccess: function () {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();

                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    balance_id: '=',
                    customer_id: '=',
                    used_balance_id: '=',
                    'customer.ctm_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                });
            });
            $('#a-search-customer').on('click', function() {
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        comselectpop: function () {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            $('.fixed-table-toolbar .dropdown-menu').parent().remove();
            Controller.api.bindevent();

            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();

                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    balance_id: '=',
                    customer_id: '=',
                    used_balance_id: '=',
                });
            });
            //单用户 优惠券列表
            if ($('#h-customer-id').val()) {
                $('#field_ctm_id').val($('#h-customer-id').val());
                $('#a-search-customer').parents('.form-group').addClass('hidden');
            } else {
                $('#a-search-customer').on('click', function() {
                    var params = '?mode=single';
                    Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
                });
                $('#btn-customer-clear').on('click', function() {
                    $('#field_ctm_id').val('');
                    $('#field_ctm_name').val('');
                })
            }
            
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});