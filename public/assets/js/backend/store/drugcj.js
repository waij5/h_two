define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table, Form,undefined) {
//日期插件 'bootstrap-datetimepicker'     undefined
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugcj/index',
                    add_url: 'store/drugcj/add',
                    edit_url: 'store/drugcj/edit',
                    // del_url: 'store/drugcj/del',
                    multi_url: 'store/drugcj/multi',
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
                        {field: 'is_drug_text', title: __('Is_drug'), operate:false},
                        {field: 'cj_type_text', title: __('Cj_type'), operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark')},
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
                    'yjy_purchase_order.id':"=",
                    order_num: '=',
                    depot_id: '=',
                    'producer_id': '=',
                    stime: '',
                    etime: '',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
			
            $('#selectedDrugs').on('input', 'input', function() {
				var type = $(this).attr('class');
				var tr = $(this).parents('tr');
				var data = $(this).val();
				updateRowData(type, tr, data);
				
			});

            $('.getNum').click(function(){
                var num = 1;
                var url = '/store/drugcj/getNum';
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
            // alert(i);
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
                var url = '/store/drugcj/proSearch';
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
                        $('#word').append('<li class="onloading">拼音码<<>>名称<<>>批号<<>>当前库存<<>>规格<<>>单位<<>>到期日期</li>');
                        // alert(msg);
                        dataObj = msg;
                        // var id = '';
                        $.each(dataObj, function(index,item){
                            $('#word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + item.id + '"< >' + item.code + '< >'+ item.name + '< >'+ item.lotnum+'< >'+ item.stock +'< >'+ item.sizes +'< >'+ item.unit +'< >'+ item.extime +'</li>');
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
                    var rowHtml = '<tr class="clearNav"  id="ps_'+dataObj[i]['id']+'" data-ps="' + dataObj[i]['id'] + '" ><td>' + '<input type="hidden" name="drugs_id[]" value="' + dataObj[i]['id'] + '" />' + dataObj[i]['name'] + '</td><td>' + dataObj[i]['lotnum'] + '</td><td>' + dataObj[i]['stock'] + '</td><td><input class="sto_num" type="text" value="1" name="storage_num[]" size="4"></td><td>' + dataObj[i]['sizes'] + '</td><td>' + dataObj[i]['unit'] + '</td><td><input class="cost" readonly type="text" value="' + dataObj[i]['cost'] + '" name="cost[]"  size="4"></td><td><input type="hidden" value="' + dataObj[i]['price'] + '" name="price[]"  size="4"><input type="text" readonly value="' + dataObj[i]['cost'] + '" class="totalcost" name="totalcost[]"  size="4"></td><td><input type="text" class="datetimepicker " data-date-format="YYYY-MM-DD" name="producttime[]" size="7" readonly  value="' + dataObj[i]['prtime'] + '" /></td><td><input type="text" value="' + dataObj[i]['extime'] + '" readonly class="datetimepicker " data-date-format="YYYY-MM-DD" name="expirestime[]"  size="7"></td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';

                    
                    $('#selectedDrugs').append(rowHtml);
                    
                }
            };
            // console.log(ps);   
        }


            
            $('#sure').on('click', function() {
                $("#c-type").removeAttr("disabled");
                $("#c-is_drug").removeAttr("disabled");
            });
            
            //修改了所属仓库则清除当前选择的药品
            $('#c-depot_id').change(function(){

                /*3.24选择药品显示修改  所属仓库改变后清除记录列表所有产品的id的变量;且搜索框中内容清空*/
                ps = Array();
                $('#word').html('');
                $('.keyword').val('');

                $('.clearNav').remove();
            })

            $('#selectedDrugs').on('click', '[name="drugRemoveBtn"]', function() {
                
                /*3.24选择药品显示修改  药品列表删除单个药品时清除该记录id*/
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];

                $(this).parents('tr').remove();
            });
           
            $('#clear').on('click', function() {

                /*3.24选择药品显示修改  药品列表清空时清除记录列表所有产品的id的变量*/
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
                    $('#depotName').html('仓库：'+depotName);
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
            
        },
		
        api: {
            bindevent: function () {        
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    
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
    // <input id="c-ctm_birthdate"  name="row[ctm_birthdate]" type="text" value="">

});