define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table, Form,undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugstock/index',
                    add_url: 'store/drugstock/add',
                    edit_url: 'store/drugstock/edit',
                    // del_url: 'store/drugstock/del',
                    multi_url: 'store/drugstock/multi',
                    sel_url: 'store/drugstock/selectdrug',
                    // allot_url: 'store/drugstock/allot',
                    table: 'purchase_order',
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
                        {field: 'order_num', title: __('Order_num')},
                        {field: 'uid', title: __('Uid')},
                        {field: 'proname', title: __('Producer_id')},
                        {field: 'depname', title: __('Depot_id')},
                        {field: 'allot_type', title: '调拨状态', formatter: function(value,row,index){if(value==1){return '<b style="color: red">未调拨</b>'}else if(value==2){return '<b style="color: #18bc9c">已调拨</b>'}}},
                        {field: 'type_text', title: __('Type'), operate:false},
                        {field: 'is_jz_text', title: __('Is_jz'), operate:false},
                        {field: 'is_cb_text', title: __('Is_cb'), operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
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
                    'yjy_purchase_order.id': '=',
                    order_num: '=',
                    depot_id: '=',
                    producer_id: '=',
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

             $('#selectedDrugs').on('input', '.sto_num', function() {
				var type = $(this).attr('class');
				var tr = $(this).parents('tr');
				var data = $(this).val();
				updateRowData(type, tr, data);
				
			});

            $('.getNum').click(function(){
                var num = 1;
                var url = '/store/drugstock/getNum';
                // alert(num);
                $.ajax({
                        type: 'POST',
                        url: url,
                        data: {num:num},
                        dataType: 'json',
                        success: function(msg){
                            // alert(msg);
                            // var mss = eval(msg);
                            $('#c-order_num').attr('value', msg);
                            // 
                        }
                    })
            })
            


        var dataObj = new Array();
        var ps = new Array();
        $('#pro_search').keyup(function(){
            var keywords = $(this).val();  
            productSearch(keywords); 
            
        });
        $('#word').on('click', 'li', function() {
            var i = $(this).data('index');
            selectProduct(i);
        });
        

        function productSearch(keywords){
            $('#word').removeClass('hidden')
            $('#word').empty().show();
            
            if(keywords == '') {
                $('#word').hide();
                return
            };
            var depot = $('#c-depot_id').val();

            // $('#c-remark').attr('value', keywords+depot);
            if(keywords && depot){
                var url = '/store/drugstock/proSearch';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {keywords:keywords, depot:depot},
                    dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                    beforeSend: function() {

                        $('#word').append('<li class="onloading">正在加载。。。</li>');
                    },
                    success: function(msg){
                        $('#word').html('');
                        $('#word').append('<li class="onloading">拼音码<<>>名称<<>>批号<<>>规格<<>>单位</li>');
                        // alert(msg);
                        dataObj = msg;
                        // var id = '';
                        $.each(dataObj, function(index,item){
                            $('#word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + item.id + '">' + item.code + '< >'+ item.name + '< >'+ item.lotnum+'< >'+ item.sizes +'< >'+ item.unit +'</li>');
                        })
                        
                        $('#word').removeClass('hidden');
                        $('#word').show();
                        $('#word').append('<li class="" style="text-align:center">----------------------已全部加载----------------------</li>');
                    }
                })
            }
        }
            

        function selectProduct(i){
            if(i){
                var dataId = dataObj[i]['id'];
            // var trList = parseFloat($(".clearNav").data('ps'));
                $('.clearNav').each(function(){
                    pss = $(this).data('ps');
                    ps[pss] = pss;
                    
                });

                if(!ps[dataId]){
                    // alert('不存在重复');
                    var rowHtml = '<tr class="clearNav " id="ps_'+dataObj[i]['id']+'" data-ps="' + dataObj[i]['id'] + '" ><td>' + '<input type="hidden" name="drugs_id[]" value="' + dataObj[i]['id'] + '" />' + dataObj[i]['name'] + '</td><td>' + dataObj[i]['lotnum'] + '</td><td>' + dataObj[i]['stock'] + '</td><td><input class="sto_num" type="text" value="1" name="storage_num[]" size="4"></td><td>' + dataObj[i]['sizes'] + '</td><td>' + dataObj[i]['unit'] + '</td><td><input class="cost" readonly type="text" value="' + dataObj[i]['cost'] + '" name="cost[]"  size="4"></td><td><input type="hidden" value="' + dataObj[i]['price'] + '" name="price[]"  size="4"><input type="text" readonly value="' + dataObj[i]['cost'] + '" class="totalcost" name="totalcost[]"  size="4"></td><td><input type="text" value="' + dataObj[i]['prtime'] + '" class="datetimepicker" data-date-format="YYYY-MM-DD" name="producttime[]" size="7"></td><td><input type="text" value="' + dataObj[i]['extime'] + '" class="datetimepicker" data-date-format="YYYY-MM-DD" name="expirestime[]"  size="7"></td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                    var appendEle = $(rowHtml);
                    $('#selectedDrugs').append(appendEle);
                    $(appendEle).find('.datetimepicker').parent().css('position', 'relative');   //日期插件
                    $(appendEle).find('.datetimepicker').datetimepicker(dateDrugcj);
                }
            };
            // console.log(ps);   
        }




            $('#sure').on('click', function() {
                $("#c-type").removeAttr("disabled");

            });
            
        
            $('#c-depot_id').change(function(){

                /*3.23选择药品显示修改  所属仓库改变后清除记录列表所有产品的id的变量;且搜索框中内容清空*/
                ps = Array();
                $('#word').html('');
                $('.keyword').val('');

                $('.clearNav').remove();
                
            })

            $('#selectedDrugs').on('click', '[name="drugRemoveBtn"]', function() {

                /*3.23选择药品显示修改  药品列表删除单个药品时清除该记录id*/
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];
                // console.log(ps);

                $(this).parents('tr').remove();
            });
           
            $('#clear').on('click', function() {

                /*3.23选择药品显示修改  药品列表清空时清除记录列表所有产品的id的变量*/
                ps = Array();

                $('.clearNav').remove();
            });




            
        },
        edit: function () {
            Controller.api.bindevent();

            //4.10  打印 打印功能
            $('#isPrint').click(function(){

                

                // $('.printRemove').remove();    //指定打印内容中多余部分移除
                if(confirm('是否开始打印?')){

                    
                    var depotName = $('#c-depot_id option:selected').text();
                    var producerName = $('#c-producer_id option:selected').text();
                    $('#depotName').html('仓库：'+depotName);
                    $('#producerName').html('供应商：'+producerName);
                    $('.printA').css('display','');
                    


                    bdhtml=window.document.body.innerHTML;
                    sprnstr="<!--startprint-->"; //开始打印标识字符串有17个字符
                    eprnstr="<!--endprint-->"; //结束打印标识字符串
                    prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); //从开始打印标识之后的内容
                    prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); //截取开始标识和结束标识之间的内容
                    window.document.body.innerHTML=prnhtml; //把需要打印的指定内容赋给body.innerHTML
                    window.print(); //调用浏览器的打印功能打印指定区域
                }else{
                    return false;
                }
            });


            $('#btn-allot').on('click',function(){
                if(confirm('是否确认调拨?')){
                    var allot_url = '/store/drugstock/allot';
                    var c_id = $('#c-id').val();
                    var in_depot = $('#in_depot_id').val();
                    var allot_type = '2';       //调拨状态：1、未调拨；2、已调拨
                    // alert(order_num);
                    $.ajax({
                        type: 'POST',
                        url: allot_url,
                        data: {c_id:c_id,allot_type:allot_type,in_depot:in_depot},
                        success: function(msg){
                            // var mss = eval(msg);
                            if(msg == '1'){
                               Toastr.success('调拨成功！');
                            }else if(msg == '2'){
                               Toastr.error('调拨失败！') ;
                            }
                           
                            location.reload();
                        }
                    })
                    // alert(url);
                    // Fast.api.open(params, __('Edit'));
                    return false;
                }
            })
			
        },
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    
    // function removeSelectedDrug(ele) {
    //     $(ele).parents('tr').remove();
    // }
	function updateRowData(type, tr, data) {
		var storage_num;
		var cost;
		var total_cost;
		if(type.indexOf("sto_num") >= 0) {
            storage_num = parseInt(data);
            cost = parseFloat($(tr).find('[name="cost[]"]').val());
            total_cost = (storage_num * cost).toFixed(2);
            $(tr).find('[name="totalcost[]"]').val(total_cost);

        } else if(type.indexOf("cost") >= 0){
            cost = parseFloat(data);
            storage_num = parseInt($(tr).find('[name="storage_num[]"]').val());
            total_cost = (storage_num * cost).toFixed(2);
            $(tr).find('[name="totalcost[]"]').val(total_cost);
        }
        
		
	}

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