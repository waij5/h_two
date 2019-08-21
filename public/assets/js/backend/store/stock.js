define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/stock/index',
                    // add_url: 'store/stock/add',
                    edit_url: 'store/stock/edit',
                    // del_url: 'store/stock/del',
                    multi_url: 'store/stock/multi',
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
                        // {checkbox: true},
                        {field: 'id', title: '序号', formatter: function(value,row,index){return index+1}},
                        {field: 'num', title: __('Num')},
                        {field: 'name', title: __('Name')},
                        {field: 'lotnum', title: '批号'},
                        {field: 'code', title: __('Code')},
                        {field: 'dname', title: __('Depot_id')},
                        {field: 'sizes', title: __('Sizes')},
                        {field: 'unit', title: __('Unit')},
                        {field: 'stock', title: __('Stock')},
                        // {field: 'price', title: '应销价格'},
                        // {field: 'totalprice', title: '应销总额'},
                        {field: 'remark', title: __('Remark')},
                        {field: 'operate', title: '操作', table: table,events: Table.api.events.operate, 
                        formatter: Table.api.formatter.operate}
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
                    'yjy_product.name': 'LIKE %...%',
                    'yjy_product.lotnum': '=',
                    'yjy_product.code': 'LIKE %...%',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            // $('.btn-danger').attr('style','display:none');
        },

        edit: function () {
            Controller.api.bindevent();

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/stock/edit',
                    table: 'stock_log',
                    title: '20',
                }
            });

            var table = $("#table");
            // 初始化表格
            var ids = $('#h-ids').val();
            table.bootstrapTable({
                url: 'store/stock/edit?ids='+ ids,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [   
                        {field: 'idsss', title: '序号', formatter: function(value,row,index){return index+1}},
                        {field: 'l_time', title: '时间', formatter: Table.api.formatter.datetime},
                        {field: 'l_type', title: '类型'},
                        {field: 'l_etc', title: '业务单号'},
                        {field: 'l_num', title: '数量'},
                        {field: 'l_rest', title: '库存结余'},
                        {field: 'l_cost', title: '成本单价'},
                        {field: 'l_money', title: '总成本'},
                        {field: 'l_price', title: '零售价'},
                        {field: 'dept_name', title: '科室'},
                        {field: 'proname', title: '供应商'},
                        {field: 'l_explain', title: '说明'},
                        {field: 'l_remark', title: '备注'}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            $('.fixed-table-toolbar').attr('style','display:none');   //隐藏搜索框

        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});