define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/deptprorecords/index',
                    add_url: 'customer/deptprorecords/add',
                    edit_url: 'customer/deptprorecords/edit',
                    del_url: 'customer/deptprorecords/del',
                    multi_url: 'customer/deptprorecords/multi',
                    table: 'dept_pro_records',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                commonSearch: false,
                search: false,
                pk: 'Id',
                sortName: 'Id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'Id', title: __('Id')},
                        {field: 'order_num', title: __('Order_num')},
                        // {field: 'pro_name', title: __('Pro_id')},
                        // {field: 'pro_num', title: __('Pro_num')},
                        {field: 'ctm_name', title: __('Customer_id')},
                        {field: 'nickname', title: __('Admin_id')},
                        {field: 'dept_name', title: __('Dept_id')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                    pro_id: '=',
                    order_num: '=',
                    customer_id: '=',
                    admin_id: '=',
                    dept_id: '=',
                    createtime: 'BETWEEN',
                });
            });
        },
        add: function () {
            Controller.api.bindevent();
            //物品页面
            document.getElementById("btn-sel").onclick=function(e){
                Fast.api.open("customer/deptprorecords/selectgoods",'药品列表');
            };

            $('#clear').on('click', function() {
                $('.clearNav').remove();
            });

            $('#selectedGoods').on('click', '[name="goodsRemoveBtn"]', function() {
                $(this).parents('tr').remove();
            });

        },
        edit: function () {
            Controller.api.bindevent();
        },

        selectgoods: function(){
             Table.api.init({
                extend: {
                    index_url: 'customer/deptpro/index',
                    add_url: 'customer/deptpro/add',
                    edit_url: 'customer/deptpro/edit',
                    del_url: 'customer/deptpro/del',
                    multi_url: 'customer/deptpro/multi',
                    table: 'dept_pro',
                }        
            });
            var table = $("#table");
            
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'Id',
                sortName: 'Id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'Id', title: __('Id')},
                        {field: 'pro_name', title: __('Pro_name')},
                        {field: 'pro_sizes', title: __('Pro_sizes')},
                        {field: 'pro_unit', title: __('Pro_unit')},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                    id: '=',
                    pro_name: 'LIKE %...%',
                });
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            
             //此处是传值
            var curIndex = parent.layer.getFrameIndex(window.name);//获取子窗口索引

            $('#sure').on('click', function() {
                var ids = Table.api.selectedids(table);
                var typeVal = $('#c-type').val();
                $.map(table.bootstrapTable('getSelections'), function (goodrow) {  
                        var rowHtml = '<tr class="clearNav"><td style="vertical-align:middle">' + '<input type="hidden" name="drugs_id[]" value="' + goodrow['Id'] + '" />' +goodrow['Id']+ '</td><td style="vertical-align:middle">' + goodrow['pro_name'] + '</td><td style="width:12%"><input type="number" class="sto_num form-control" value="1" name="storage_num[]" min="1"></td><td style="vertical-align:middle">' + goodrow['pro_unit'] + '</td>'+ '</td><td style="vertical-align:middle">' + '<a href="javascript:;" name="goodsRemoveBtn" class="btn btn-xs btn-danger btn-delone" title="删除"><i class="fa fa-trash"></i></a>' + '</td></tr>';
                        parent.$('#selectedGoods').append(rowHtml);
                    });
                parent.layer.close(curIndex);
            })
        },

        api: {
            bindevent: function () {

            //顾客
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            //清除顾客
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});