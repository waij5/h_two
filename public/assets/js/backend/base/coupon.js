define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/coupon/index',
                    add_url: 'base/coupon/add',
                    edit_url: 'base/coupon/edit',
                    del_url: 'base/coupon/del',
                    multi_url: 'base/coupon/multi',
                    table: 'coupon',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'pay_amount', title: __('Pay_amount')},
                        {field: 'amount', title: __('Amount')},
                        {field: 'usage_limit', title: __('Usage_limit')},
                        {field: 'usage_per_customer', title: __('Usage_per_customer')},
                        {field: 'expiration', title: __('Expiration'), formatter: Table.api.formatter.datetime},
                        {field: 'remark', title: __('Remark')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
       

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            $('#s-row-expiration').on('blur', function() {
                $('#h-row-expiration').val(parseInt(Moment($('#s-row-expiration').val()) / 1000));
            });
        },
        edit: function () {
            Controller.api.bindevent();
            $('#s-row-expiration').on('blur', function() {
                $('#h-row-expiration').val(parseInt(Moment($('#s-row-expiration').val()) / 1000));
            });
        },
        comselectpop: function () {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});