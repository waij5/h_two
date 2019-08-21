define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/goods/index',
                    add_url: 'store/goods/add',
                    edit_url: 'store/goods/edit',
                    // del_url: 'store/goods/del',
                    multi_url: 'store/goods/multi',
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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'num', title: __('Num')},
                        {field: 'name', title: __('Name')},
                        {field: 'code', title: __('Code')},
                        {field: 'lotnum', title: '批号'},
                        {field: 'price', title: '物品售价'},
                        // {field: 'thisprice', title: __('Thisprice')},
                        // {field: 'lowprice', title: __('Lowprice')},
                        {field: 'cost', title: '物品成本'},
                        {field: 'dept_name', title:'所属仓库'},
                        {field: 'pdutype_id', title: __('Pdutype_id')},
                        {field: 'pdutype2_id', title: __('Pdutype2_id')},
                        {field: 'sizes', title: __('Sizes')},
                        {field: 'unit', title: __('Unit')},
                        // {field: 'lowunit', title: __('Lowunit')},
                        // {field: 'hex', title: __('Hex')},
                        {field: 'stock_top', title: __('Stock_top')},
                        {field: 'stock_low', title: __('Stock_low')},
                        {field: 'stock', title: __('Stock')},
                        {field: 'drug_type_text', title: '物品类型', operate:false},
                        {field: 'addr', title: __('Addr')},
                        {field: 'producer_id', title: __('Producer_id')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                    // 'yjy_purchase_order.id': '=',
                    num: '=',
                    name: 'LIKE %...%',
                    pdutype_id: '=',
                    pdutype2_id: '=',
                    status: '=',
                    stime: '',
                    etime: '',
                    depot_id: '=',
                    code: 'LIKE %...%',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));

            $('#c-pdutype_id').change(function(){
                    var subject_id = $(this).val();
                    var url = '/store/goods/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pdutype2_id').html(msg);
                            // alert(msg);
                        }
                    })
                    // alert(subject_id);
                })
            
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();

            $("#c-price").change(function(){
                var price = $(this).val();
                $('#c-thisprice').attr('value', price);
            })

            $('.getNum').click(function(){
                var num = 1;
                var url = '/store/goods/getNum';
                // alert(num);
                $.ajax({
                        type: 'POST',
                        url: url,
                        data: {num:num},
                        dataType: 'json',
                        success: function(msg){
                            // alert(msg);
                            // var mss = eval(msg);
                            $('#c-num').attr('value', msg);
                            // 
                        }
                    })
            })


            // <!-- 2017-09-27  子非魚 --> 
                $('#c-pdutype_id').change(function(){
                    var subject_id = $(this).val();
                    var url = '/store/goods/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pdutype2_id').html(msg);
                            // alert(msg);
                        }
                    })
                    // alert(subject_id);
                })
                // <!-- 2017-09-27  子非魚 -->

        },
        edit: function () {
            Controller.api.bindevent();

            $("#c-price").change(function(){
                var price = $(this).val();
                $('#c-thisprice').attr('value', price);
            })
            
            // <!-- 2017-09-27  子非魚 --> 
                $('#c-pdutype_id').change(function(){
                    var subject_id = $(this).val();
                    var url = '/store/goods/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pdutype2_id').html(msg);
                            // alert(msg);
                        }
                    })
                    // alert(subject_id);
                })
                // <!-- 2017-09-27  子非魚 -->
        },
        comselectpop: function () {
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
                    type: '=',
                    code: 'LIKE %...%',
                    name: 'LIKE %...%',
                });
            });
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