define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/validitywarn/index',
                    add_url: 'store/validitywarn/add',
                    edit_url: 'store/validitywarn/edit',
                    del_url: 'store/validitywarn/del',
                    multi_url: 'store/validitywarn/multi',
                    table: 'product',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'code',
                search: false,
                commonSearch: false,
                columns: [
                    [   
                        // {checkbox: true},
                        // {field: 'onum', title: '序号'},
                        {field: 'dd',  title: '序号',formatter: function(value,row,index) {return index+1}},    //获取序号
                        {field: 'num', title: '产品编号'},
                        {field: 'name', title: '产品名称'},
                        {field: 'lotnum', title: '批号'},
                        {field: 'depot_name', title: '所属仓库'},
                        {field: 'pdutype_id', title: '类型'},
                        {field: 'pdutype2_id', title: '类别'},
                        // {field: 'good_num', title: '进货数量'},
                        {field: 'stock', title: '现有库存'},
                        {field: 'prtime', title: '生产日期', formatter: Table.api.formatter.datetime},
                        {field: 'extime', title: '失效日期', formatter: Table.api.formatter.datetime},
                        {field: 'remark', title: '说明'},
                        
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
                    'yjy_product.num': '=',
                    'yjy_product.name': 'LIKE %...%',
                    // 'yjy_purchase_flow.expirestime': '=',
                    stime: '',
                    etime: '',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));
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

    var dateDrugcj =    //日期插件
    {
        format: 'YYYY-MM-DD HH:mm:ss',
        icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-history',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
        },
        showTodayButton: true,
        showClose: true
    };
    return Controller;
});