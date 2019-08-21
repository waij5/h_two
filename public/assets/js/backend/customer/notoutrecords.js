define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/notoutrecords/index',
                    add_url: 'customer/notoutrecords/add',
                    edit_url: 'customer/notoutrecords/edit',
                    del_url: 'customer/notoutrecords/del',
                    multi_url: 'customer/notoutrecords/multi',
                    table: 'notout_records',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                commonSearch: false,
                search: false,
                pk: 'rec_id',
                sortName: 'rec_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'rec_id', title: __('Rec_id')},
                        // {field: 'customer_id', title: __('Customer_id')},
                        {field: 'ctm_name', title: __('Customer_id')},
                        {field: 'deposit_amt', title: __('Deposit_amt')},
                        {field: 'undeducted_total', title: __('Undeducted_total')},
                        {field: 'not_out_total', title: __('Not_out_total')},
                        {field: 'stat_date', title: __('Stat_date')},
                        {field: 'status', title: __('Status'), formatter: Backend.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            Controller.api.bindevent();

            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();

                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    customer_id: '=',
                    deposit_amt: 'BETWEEN',
                    undeducted_total: 'BETWEEN',
                    not_out_total: 'BETWEEN',
                    'rec.stat_date': '=',
                    'customer.ctm_id': '=',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_mobile': 'LIKE %...%',
                });
            });

        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));

                $('#a-search-customer').on('click', function() {
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
                });
                
                $('#btn-customer-clear').on('click', function() {
                    $('#field_ctm_id').val('');
                    $('#field_ctm_name').val('');
                });

            }
        }
    };
    return Controller;
});