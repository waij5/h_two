define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/lowstock/index',
                    add_url: 'store/lowstock/add',
                    edit_url: 'store/lowstock/edit',
                    del_url: 'store/lowstock/del',
                    multi_url: 'store/lowstock/multi',
                    table: 'product',
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
                        {field: 'id', title: __('Id')},
                        {field: 'num', title: __('Num')},
                        {field: 'name', title: __('Name')},
                        {field: 'lotnum', title: '批号'},
                        {field: 'code', title: __('Code')},
                        {field: 'sizes', title: __('Sizes')},
                        {field: 'stock', title: __('Stock')},
                        {field: 'stock_low', title: __('Stock_low')},
                        {field: 'stock_top', title: __('Stock_top')},
                        {field: 'buynum', title: __('Buynum')},
                        {field: 'maxbuynum', title: __('Maxbuynum')},
                        {field: 'remark', title: __('Remark')}
                    ]
                ]
            });

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    num: '=',
                    name: 'LIKE %...%',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
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
            }
        }
    };
    return Controller;
});