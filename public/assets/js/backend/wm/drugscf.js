define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index_one: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wm/drugscf/index_one',
                    edit_url: 'wm/drugscf/edit_one',
                    order_edit_url: 'cash/order/edit',
                    table: 'depot_outks',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'order_item_id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                [
                    {
                        field: 'id',
                        title: '划扣ID'
                    },
                    {
                        field: 'order_item_id',
                        title: '处方单ID'
                    }, {
                        field: 'item_type',
                        title: __('Order_type'),
                        formatter: function(value, row, index) {
                            return __('Order_type_' + value);
                        }
                    }, 

                    {
                        field: 'ctm_name',
                        title: __('Ctm_name')
                    }, {
                        field: 'customer_id',
                        title: __('Ctm_id')
                    }, 
                    
                    {
                        field: 'pro_name',
                        title: __('pro_name'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    },
                    {
                        field: 'deduct_times',
                        title: '数量',
                    }, {
                        field: 'item_total_times',
                        title: __('item_total_times'),
                    },
                    {
                        field: 'pro_spec',
                        title: __('pro_spec'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    },
                    {
                        field: 'item_paytime',
                        title: '付款时间',
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                        formatter: Backend.api.formatter.datetime
                    },
                    {
                        field: 'createtime',
                        title: '划扣时间',
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                        formatter: Backend.api.formatter.datetime
                    },
                    {field: 'operate', title: __('Operate'), events: {
                        'click .btn-editone': function(e, value, row, index) {
                            e.stopPropagation();
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.sortName], '发药'+' id '+ row[options.sortName]);
                        },
                    }, formatter: function(value, row, index, custom) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="发药"><i class="fa fa-mail-forward"></i></a>';
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
                    'deduct_records.id': '=',
                    customer_id: '=',
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
                    index_url: 'wm/drugscf/index_two',
                    edit_url: 'wm/drugscf/edit_two',
                    order_edit_url: 'cash/order/edit',
                    table: 'depot_outks',
                }
            });

            var table = $("#table");
            Form.events.datetimepicker($("form[role=form]"));
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                [
                    {
                        field: 'id',
                        title: '划扣ID'
                    },
                    {
                        field: 'order_item_id',
                        title: '处方单ID'
                    }, {
                        field: 'item_type',
                        title: __('Order_type'),
                        formatter: function(value, row, index) {
                            return __('Order_type_' + value);
                        }
                    }, 

                    {
                        field: 'ctm_name',
                        title: __('Ctm_name')
                    }, {
                        field: 'customer_id',
                        title: __('Ctm_id')
                    }, 
                    
                    {
                        field: 'pro_name',
                        title: __('pro_name'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    },
                    {
                        field: 'deduct_times',
                        title: '数量',
                    }, {
                        field: 'item_total_times',
                        title: __('item_total_times'),
                    },
                    {
                        field: 'pro_spec',
                        title: __('pro_spec'),
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                    },
                    {
                        field: 'item_paytime',
                        title: '付款时间',
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                        formatter: Backend.api.formatter.datetime
                    },
                    {
                        field: 'createtime',
                        title: '划扣时间',
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                        formatter: Backend.api.formatter.datetime
                    },
                    {
                        field: 'sltime',
                        title: '发药时间',
                        cellStyle: function(value) {
                            return {
                                css: {
                                    'width': '120px',
                                    'min-width': '120px',
                                    "word-wrap": "normal",
                                    'text-align': 'left',
                                }
                            };
                        },
                        formatter: Backend.api.formatter.datetime
                    },

                    {field: 'operate', title: __('Operate'), events: {
                        'click .btn-editone': function(e, value, row, index) {
                            e.stopPropagation();
                            var options = $(this).closest('table').bootstrapTable('getOptions');
                            Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.sortName], '撤药'+' id '+ row[options.sortName]);
                        },
                    }, formatter: function(value, row, index, custom) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="撤药"><i class="fa fa-reply"></i></a>';
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
                    'deduct_records.id': '=',
                    customer_id: '=',
                    stime: '',
                    etime: '',
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



        
        edit_one: function () {
            Table.api.init({
                extend: {
                    index_url: 'wm/drugscf/edit_one',
                }
            });
            var ps = new Array();
            $('#words').on('click', 'tr', function() {
                var lot_id = $(this).data('index');        //
                var deduct_amount = $('.deduct_amount').val();//划扣总金额 = slallprice
                var url = '/wm/drugscf/edit_one';
                $.ajax({
                    type: 'POST',
                        url: url,
                        data: {lot_id:lot_id},
                        dataType: 'json',
                        success: function(msg){
                            // alert(msg);
                            // console.log(msg);
                            var msg = msg;
                            msgReturn(msg);
                            // $('#c-man_num').attr('value', msg);
                            // 
                        }
                })
                // selectProduct(i);
                // console.log(lot_id);
            });
            $('#clear').on('click', function() {

                /*选择药品显示修改  药品列表清空时清除记录列表所有产品的id的变量*/
                ps = Array();

                $('.clearNav').remove();
            });
            $('#selectedDrugs').on('click', '[name="drugRemoveBtn"]', function() {

                /*选择药品显示修改  药品列表删除单个药品时清除该记录id*/
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];
                // console.log(ps);

                $(this).parents('tr').remove();
            });

            Controller.api.bindevent();


            function msgReturn(msg){
                if(msg['lot_id']){
                    var dataId = msg['lot_id'];
                    // console.log(i);
                    $('.clearNav').each(function(){
                        pss = $(this).data('ps');
                        ps[pss] = pss;
                        
                    });

                    if(!ps[dataId]){
                        // alert('不存在重复');
                        var rowHtml = '<tr class="clearNav " id="ps_'+msg['lot_id']+'" data-ps="' + msg['lot_id'] + '" ><td>' + '<input type="hidden" name="lot_id[]" value="' + msg['lot_id'] + '" />' + msg['pro_name'] + '</td><td>' + msg['lotnum'] + '</td><td>'+ msg['lstock'] +'</td><td><input class="mpro_num" type="text" value="1" name="mpro_num[]" size="5"></td><td>' + msg['dname'] + '</td><td>' + msg['pro_spec'] + '</td><td>' + msg['uname'] + '</td><td><input class="mcost" readonly  type="text" value="' + msg['lcost'] + '" name="mcost[]"  size="4"></td><td><input class="mprice" readonly  type="text" value="' + msg['lprice'] + '" name="mprice[]"  size="4"></td><td>' + msg['lstime'] + '</td><td>' + msg['letime'] + '</td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                        var appendEle = $(rowHtml);
                        $('#selectedDrugs').append(appendEle);

                    }
                };
                // console.log(ps);   
            }

            $('#sureFy').click(function(){

                
                var deduct_times = $("input[name='deduct_times']").val();
                var customer_id = $("input[name='customer_id']").val();
                var dr_id = $("input[name='dr_id']").val();
                var type = $("input[name='type']").val();

                var lotidData = $("input[name='lot_id[]']");
                var lot_id = new Array();

                var mnumData = $("input[name='mpro_num[]']");
                var mpro_num = new Array();

                var mcostData = $("input[name='mcost[]']");
                var mcost = new Array();

                var mpriceData = $("input[name='mprice[]']");
                var mprice = new Array();

                for (var i = 0; i < lotidData.length; i++) {
                    lot_id[i] = lotidData.eq(i).val();
                    mpro_num[i] = mnumData.eq(i).val();
                    mcost[i] = mcostData.eq(i).val();
                    mprice[i] = mpriceData.eq(i).val();
                }

                // console.log(lot_id+'**'+mpro_num+'**'+mcost+'**'+re_id+'**'+mprice);
                var baseUrl = '/wm/drugscf/dispensing';
                $.ajax({
                    type: 'POST',
                    url: baseUrl,
                    data: {deduct_times:deduct_times,customer_id:customer_id,dr_id:dr_id,lot_id:lot_id,mpro_num:mpro_num,mcost:mcost,mprice:mprice},
                    dataType: 'json',
                    success: function(msg){
                        // var mss = eval(msg);
                        if(type=='2'){
                            if(msg == '1'){
                                Fast.api.close('1');
                            }else if(msg == '2'){
                                Fast.api.close('2') ;
                            }else{
                                Toastr.error(msg['msg']);
                            }
                        }
                        
                    }
                })

            });

			
        },


        

        edit_two: function(){
            Table.api.init({
                extend: {
                    index_url: 'wm/drugscf/edit_two',
                }
            });

            $('#sureCy').click(function(){

                
                var customer_id = $("input[name='customer_id']").val();
                var dr_id = $("input[name='dr_id']").val();
                var type = $("input[name='type']").val();

                var lotidData = $("input[name='lot_id[]']");
                var lot_id = new Array();

                var mnumData = $("input[name='mpro_num[]']");
                var mpro_num = new Array();

                var mcostData = $("input[name='mcost[]']");
                var mcost = new Array();

                var reidData = $("input[name='re_id[]']");
                var re_id = new Array();

                var mpriceData = $("input[name='mprice[]']");
                var mprice = new Array();

                for (var i = 0; i < reidData.length; i++) {
                    lot_id[i] = lotidData.eq(i).val();
                    mpro_num[i] = mnumData.eq(i).val();
                    mcost[i] = mcostData.eq(i).val();
                    re_id[i] = reidData.eq(i).val();
                    mprice[i] = mpriceData.eq(i).val();
                }

                // console.log(lot_id+'**'+mpro_num+'**'+mcost+'**'+re_id+'**'+mprice);
                var baseUrl = '/wm/drugscf/revoke';
                $.ajax({
                    type: 'POST',
                    url: baseUrl,
                    data: {customer_id:customer_id,dr_id:dr_id,lot_id:lot_id,mpro_num:mpro_num,mcost:mcost,re_id:re_id,mprice:mprice},
                    dataType: 'json',
                    success: function(msg){
                        // var mss = eval(msg);
                        if(type=='2'){
                            if(msg == '1'){
                                Fast.api.close('1');
                            }else if(msg == '2'){
                                Fast.api.close('2') ;
                            }
                        }
                        
                    }
                })

            });

            Controller.api.bindevent();
        },
















//          未发药列表
        undeliveriedlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wm/drugscf/undeliveriedlist',
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
                                    /*if(confirm('是否确认发药?')){
                                        e.stopPropagation;
                                        var baseUrl = '/wm/drugscf/dispensing';
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
                                                // document.getElementById('cfEditRefresh').click();$('#cfEditRefresh').click();
                                                // table.bootstrapTable('refresh', {});
                                            }
                                        })
                                        // alert(url);
                                        // Fast.api.open(params, __('Edit'));
                                        return false;
                                    }*/
                                    
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
                    index_url: 'wm/drugscf/deliveriedlist',
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
                        {field: 'operate', title: __('Operate'),
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