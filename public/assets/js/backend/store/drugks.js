define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugks/index',
                    edit_url: 'store/drugks/edit',
                    del_url: 'store/drugks/del',
                    multi_url: 'store/drugks/multi',
                    order_edit_url: 'cash/order/edit',
                    table: 'depot_outks',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'order_id',
                sortName: 'order_id',
                search: false,
                commonSearch: false,
                columns: [
                [
                    // {checkbox: false},
                    {field: 'order_id', title: __('Order_id')},
                    {field: 'order_type', title: __('Order_type'), formatter: function (value, row, index) {
                        return __('Order_type_' + value);
                    }},
                    {field: 'ctm_name', title: __('Ctm_name')},
                    {field: 'local_total', title: __('Local_total')},
                    {field: 'ori_total', title: __('Ori_total')},
                    {field: 'discount_amount', title: __('Discount_amount')},
                    {field: 'discount_percent', title: __('Discount_percent')},
                    {field: 'total', title: __('Total')},
                    {field: 'undeducted_total', title: __('Undeducted_total')},
                    {field: 'order_status', title: __('Order_status'), formatter: function (value, row) {
                        return __('order_status_' + String(value).replace('-', 'm_'));
                    }},
                    {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                    {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                    {field: 'operate', title: __('Operate'), events: {
                        'click .btn-editone': function(e, value, row, index) {
                            e.stopPropagation();
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            // Fast.api.open(options.extend.order_edit_url + (options.extend.order_edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                        },
                    }, formatter: function(value, row, index, custom) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a>';
                    }},
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
                    order_id: '=',
                    customer_id: '=',
                    // producer_id: '=',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            $('.btn-deduct-history').attr('style','display: none');
        },


        index_two: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugks/index_two',
                    add_url: 'store/drugks/add',
                    edit_url: 'store/drugks/edit',
                    del_url: 'store/drugks/del',
                    multi_url: 'store/drugks/multi',
                    order_edit_url: 'cash/order/edit',
                    table: 'depot_outks',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'order_id',
                sortName: 'order_id',
                search: false,
                commonSearch: false,
                columns: [
                [
                    // {checkbox: false},
                    {field: 'order_id', title: __('Order_id')},
                    {field: 'order_type', title: __('Order_type'), formatter: function (value, row, index) {
                        return __('Order_type_' + value);
                    }},
                    {field: 'ctm_name', title: __('Ctm_name')},
                    {field: 'local_total', title: __('Local_total')},
                    {field: 'ori_total', title: __('Ori_total')},
                    {field: 'discount_amount', title: __('Discount_amount')},
                    {field: 'discount_percent', title: __('Discount_percent')},
                    {field: 'total', title: __('Total')},
                    {field: 'undeducted_total', title: __('Undeducted_total')},
                    {field: 'order_status', title: __('Order_status'), formatter: function (value, row) {
                        return __('order_status_' + String(value).replace('-', 'm_'));
                    }},
                    {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                    {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                    {field: 'operate', title: __('Operate'), events: {
                        'click .btn-editone': function(e, value, row, index) {
                            e.stopPropagation();
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            // Fast.api.open(options.extend.order_edit_url + (options.extend.order_edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                        },
                    }, formatter: function(value, row, index, custom) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a>';
                    }},
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
                    order_id: '=',
                    customer_id: '=',
                    // producer_id: '=',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            $('.btn-deduct-history').attr('style','display: none');
        },



        add: function () {
            Controller.api.bindevent();
            document.getElementById("btn-sel").onclick=function(e){

                var val = $('#c-depot_id').val();
                if(val){
                    Fast.api.open("store/drugks/selectdrug?depot_id="+val,'药品列表');   //选择药品根据所属仓库带出相应数据；
                }else{
                    $('#c-depot_id').isValid(function(v){})
                }
            };

            $('#field_ctm_name').on('click', function() {
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            })
            
            $('#sure').on('click', function() {
                $("#c-type").removeAttr("disabled");
            });

            $('#c-depot_id').change(function(){
                $('.clearNav').remove();
            })

            $('#selectedDrugs').on('click', '[name="drugRemoveBtn"]', function() {
                $(this).parents('tr').remove();
            });
           
            $('#clear').on('click', function() {
                $('.clearNav').remove();
            });
        },
        edit: function () {
            Table.api.init({
                extend: {
                    index_url: 'store/drugks/edit',
                    table: 'deduct_records',
                }
            });

            var table = $("#t-project-select");
            // var hOrderId = $('#h-order-id').val();

            var url = $.fn.bootstrapTable.defaults.extend.index_url;
            // table.bootstrapTable({
            //     url: url,});

            $(".btn-deduct-history").on('click', function () {
                var ids = $(this).data('pk');
                Fast.api.open('deduct/records/index/order_item_id/' + ids, '划扣历史' + '(Item ID: ' + ids + ')');
            });

            $('#btn-delivery').on('click', function () {
                var undeliveriedUrl = 'store/drugks/undeliveriedlist';
                var ids = $(this).data('pk');
                Fast.api.open(undeliveriedUrl + (undeliveriedUrl.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ids, __('未出库列表(Order id: %s)', ids));
                        
            });

            $('#btn-outgo').on('click', function () {
                var deliveriedlist = 'store/drugks/deliveriedlist';
                var ids = $(this).data('pk');
                Fast.api.open(deliveriedlist + (deliveriedlist.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ids, __('已出库列表(Order id: %s)', ids));
                        
            });

            Controller.api.bindevent();
            Table.api.bindevent(table);
			
        },

//          未发药列表
        undeliveriedlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugks/undeliveriedlist',
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
                        {field: 'id', title: __('Id')},
                        // {field: 'order_item_id', title: __('Order_item_id')},
                        {field: 'item_name', title: __('Order_item_id'), formatter: function (value) {
                            return Backend.api.formatter.content(value, '', '', 12);
                        }},
                        {field: 'lotnum', title: '批号'},
                        {field: 'stock', title: '库存'},
                        {field: 'deduct_times', title: __('Deduct_times')},
                        {field: 'deduct_amount', title: __('Deduct amount')},
                        {field: 'deduct_benefit_amount', title: __('Deduct benefit amount')},
                        {field: 'status', title: __('Status'), formatter: function (value, row, index) {
                            return __('deduct_status_' + value);
                        }},
                        {field: 'extime', title: '过期日期', formatter: Table.api.formatter.datetime},
                        // {field: 'admin_nickname', title: __('Admin_id')},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: {
                                'click .btn-delivery': function (e, value, row, index) {
                                    //写完请删除此段注释
                                    //row.id 划扣记录ID \app\admin\model\DeductRecords::STATUS_COMPLETED
                                    //row.order_item_id 订单项ID
                                    //***** row.pro_id 为实际产品ID *****
                                    //***** row.deduct_times 为划扣次数，产品个数 *****
                                    //***** row.item_cost 成本单价 *****
                                    //***** row.deduct_amount   划扣金额，即为打折后的售价
                                    //***** row.order_id 处方单id 即编号 *****
                                    //下面的内容请自行调整
                                    if(confirm('是否确认发药?')){
                                        e.stopPropagation;
                                        var baseUrl = '/store/drugks/dispensing';
                                        var id = row.id;
                                        var ids = row.pro_id;
                                        var qty = row.deduct_times;
                                        var money = row.item_cost;
                                        var price = row.deduct_amount;
                                        var order_id = row.order_id;
                                        // params = 'ids=' + row.pro_id + '&qty=' + row.deduct_times + '&m' + money +row.order_id;
                                        // var url = baseUrl + (baseUrl.match(/(\?|&)+/) ? "&" +params : "?" + params);
                                        $.ajax({
                                            type: 'POST',
                                            url: baseUrl,
                                            data: {id:id,ids:ids,qty:qty,money:money,price:price,order_id:order_id},
                                            success: function(msg){
                                                // var mss = eval(msg);
                                                if(msg == '1'){
                                                   Toastr.success('发药成功！');
                                                }else if(msg == '2'){
                                                   Toastr.error('发药失败！') ;
                                                }else if(msg == '3'){
                                                   Toastr.error('发药数量大于当前库存，发药失败！') ;
                                                }
                                               
                                                table.bootstrapTable('refresh', {});
                                            }
                                        })
                                        // alert(url);
                                        // Fast.api.open(params, __('Edit'));
                                        return false;
                                    }
                                    
                                }
                            },
                            formatter: function (value, row, index) {
                                return '<a href="javascript:;" class="btn btn-xs btn-success btn-delivery" title="确认发药">' + 
                                            '<i class="fa fa-mail-forward"></i>' + 
                                        '</a>';
                            }
                        },
                    ]
                ],
                onLoadSuccess: function () {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

//          已发药列表
        deliveriedlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugks/deliveriedlist',
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
                        {field: 'id', title: __('Id')},
                        // {field: 'order_item_id', title: __('Order_item_id')},
                        {field: 'item_name', title: __('Order_item_id'), formatter: function (value) {
                            return Backend.api.formatter.content(value, '', '', 12);
                        }},
                        {field: 'lotnum', title: '批号'},
                        {field: 'stock', title: '库存'},
                        {field: 'deduct_times', title: __('Deduct_times')},
                        {field: 'deduct_amount', title: __('Deduct amount')},
                        {field: 'deduct_benefit_amount', title: __('Deduct benefit amount')},
                        {field: 'status', title: __('Status'), formatter: function (value, row, index) {
                            return __('deduct_status_' + value);
                        }},
                        {field: 'admin_nickname', title: __('Admin_id')},
                        {field: 'extime', title: '过期日期', formatter: Table.api.formatter.datetime},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: {
                                'click .btn-revokey': function (e, value, row, index) {
                                    //写完请删除此段注释
                                    //row.id 划扣记录ID \app\admin\model\DeductRecords::STATUS_COMPLETED
                                    //row.order_item_id 订单项ID
                                    //***** row.pro_id 为实际产品ID *****
                                    //***** row.deduct_times 为划扣次数，产品个数 *****
                                    //***** row.item_cost 成本单价 *****
                                    //下面的内容请自行调整
                                    if(confirm('是否撤销发药?')){
                                        e.stopPropagation;
                                        var baseUrl = '/store/drugks/revoke';
                                        var id = row.id;
                                        var ids = row.pro_id;
                                        var qty = row.deduct_times;
                                        var money = row.item_cost;
                                        var price = row.deduct_amount;
                                        var order_id = row.order_id;
                                        // params = 'ids=' + row.pro_id + '&qty=' + row.deduct_times + '&m' + money +row.order_id + row.id ;
                                        // var url = baseUrl + (baseUrl.match(/(\?|&)+/) ? "&" +params : "?" + params);
                                        $.ajax({
                                            type: 'POST',
                                            url: baseUrl,
                                            data: {id:id,ids:ids,qty:qty,money:money,price:price,order_id:order_id},
                                            success: function(msg){
                                                // var mss = eval(msg);
                                                if(msg == '1'){
                                                   Toastr.success('撤销成功！');
                                                }else if(msg == '2'){
                                                   Toastr.error('撤销失败！') ;
                                                }else if(msg == '3'){
                                                   Toastr.error('请核对当前库存，撤销失败！') ;
                                                }
                                                table.bootstrapTable('refresh', {});
                                            }
                                        })
                                        // alert(url);
                                        // Fast.api.open(params, __('Edit'));
                                        return false;
                                    }
                                    
                                }
                            },
                            formatter: function (value, row, index) {
                                return '<a href="javascript:;" class="btn btn-xs btn-primary btn-revokey" title="撤销发药">' + 
                                            '<i class="fa fa-reply"></i>' + 
                                        '</a>';
                            }
                        },
                    ]
                ],
                onLoadSuccess: function () {
                    $("[data-toggle='tooltip']").tooltip();
                }
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },




		selectdrug: function(){
             Table.api.init({
                extend: {
                    index_url: 'store/drugks/index',
                    add_url: 'store/drugks/add',
                    edit_url: 'store/drugks/edit',
                    del_url: 'store/drugks/del',
                    multi_url: 'store/drugks/multi',
                    sel_url: 'store/drugks/selectdrug',
                    table: 'product',
                }        
            });
            var table = $("#table");
            var depot_id = $('#h-depot-id').val();
            // 初始化表格
            table.bootstrapTable({
                url: 'store/drugks/selectdrug?depot_id=' + depot_id,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'num', title: __('Med_num')},
                        {field: 'name', title: __('Med_name')},
                        {field: 'code', title: __('Med_code')},
                        {field: 'stock', title: __('Med_stock')}, 
                        {field: 'unit', title: __('Med_unit')},
                        {field: 'price', title: __('Med_price')},
                        {field: 'cost', title: __('Med_cost')},
                        {field: 'remark', title: __('Med_remark')}              
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
                    num: '=',
                    name: 'LIKE %...%',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            
             //此处是传值
            var curIndex = parent.layer.getFrameIndex(window.name);//获取子窗口索引

            $('#sure').on('click', function() {
				var ids = Table.api.selectedids(table);
                var typeVal = $('#c-type').val();
                var rows= $.map(table.bootstrapTable('getSelections'), function (goodrow) {
                        var rowHtml = '<tr class="clearNav"><td>' + '<input type="hidden" name="drugs_id[]" value="' + goodrow['id'] + '" />' + goodrow['id'] + '</td><td>' + goodrow['code'] + '</td><td>' + goodrow['name'] + '</td><td>' + goodrow['stock'] + '</td><td><input type="text" value="1" name="storage_num[]" size="4"></td>'+ '</td><td><input type="text" value="' + goodrow['cost'] 
                         + '" readonly  size="4"></td><td><input type="text" value="' + goodrow['price'] + '" readonly size="4"></td>'+'<td>' + goodrow['unit'] + '</td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                        parent.$('#selectedDrugs').append(rowHtml);
                    });
                
                parent.layer.close(curIndex);
            })
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});