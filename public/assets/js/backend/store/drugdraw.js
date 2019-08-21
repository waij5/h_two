define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-select'], function($, undefined, Backend, Table, Form,bootstrapSelect) {

    var Controller = {
        index: function () {
        	$('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            $('button[type="reset"]').click(function(){
        		$('.bootstrap-select').each(function(index) {
        		var searchId = $(this).find('.selectpicker').attr('name');
        		var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
        		$(this).find('.dropdown-toggle').attr('title',defaultVal).attr('data-id',searchId).removeClass('bs-placeholder');
        		$(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
        		$(this).find('.inner').find('li').eq(0).addClass('selected active');
        		$(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
        		$(this).find('.inner').find('li').removeClass('hidden');
        		})
        	})
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/drugdraw/index',
                    add_url: 'store/drugdraw/add',
                    edit_url: 'store/drugdraw/edit',
                    // del_url: 'store/drugdraw/del',
                    multi_url: 'store/drugdraw/multi',
                    table: 'depot_outks',
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
                        // {field: 'proname', title: __('Producer_id')},
                        {field: 'depname', title: __('Depot_id')},
                        {field: 'dept_name', title: __('Depart_id')},
                        {field: 'nickname', title: __('Out_id')},
                        {field: 'ctm_name', title: __('Member_id')},
                        {field: 'uid', title: __('Uid')},
                        {field: 'type_text', title: __('Type'), operate:false},
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
                    'yjy_depot_outks.id': '=',
                    order_num: '=',
                    depot_id: '=',
                    depart_id: '=',
                    out_id: '=',
                    member_name: '',
                    stime: '',
                    etime: '',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));

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
        },
        add: function () {
            Controller.api.bindevent();

            // $('#c-depart_id').on('change',function(){
            //     var depart = $(this).val();
            //     var url = '/store/drugdraw/getLeader';
            //     // console.log(depart);
            //     $.ajax({
            //         type: 'POST',
            //         url: url,
            //         data: {depart:depart},
                    
            //         success: function(msg){
            //             // alert(msg);
            //             $('#c-out_id').html(msg);
            //             // 
            //         }
            //     })
            // });


            $('.getNum').click(function(){
                var num = 1;
                var url = '/store/drugdraw/getNum';
                // alert(num);
                $.ajax({
                        type: 'POST',
                        url: url,
                        data: {num:num},
                        dataType: 'json',
                        success: function(msg){
                            $('#c-order_num').attr('value',msg);
                        }
                    })
            });


            
            
    



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
            $('#selectedDrugs').on('input', 'input', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                
            });




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
                var url = '/store/drugdraw/proSearch';
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
                    var rowHtml = '<tr class="clearNav"  id="ps_'+dataObj[i]['id']+'" data-ps="' + dataObj[i]['id'] + '" ><td>' + '<input type="hidden" name="drugs_id[]" value="' + dataObj[i]['id'] + '" />' + dataObj[i]['name'] + '</td><td>' + dataObj[i]['lotnum'] + '</td><td>' + dataObj[i]['stock'] + '</td><td><input class="sto_num" type="text" value="1" name="storage_num[]" size="4"></td><td>' + dataObj[i]['sizes'] + '</td><td>' + dataObj[i]['unit'] + '</td><td><input class="cost" readonly type="text" value="' + dataObj[i]['cost'] + '" name="cost[]"  size="4"></td><td><input type="text" readonly value="' + dataObj[i]['cost'] + '" class="totalcost" name="totalcost[]"  size="4"></td><td><input type="text" value="' + dataObj[i]['price'] + '" name="price[]"  size="4" readonly></td><td><input type="text" class="datetimepicker " data-date-format="YYYY-MM-DD" name="producttime[]" size="7" readonly  value="' + dataObj[i]['prtime'] + '" /></td><td><input type="text" value="' + dataObj[i]['extime'] + '" readonly class="datetimepicker " data-date-format="YYYY-MM-DD" name="expirestime[]"  size="7"></td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';

                        $('#selectedDrugs').append(rowHtml);
                }
            };
            // console.log(ps);   
        }




            $('#c-depot_id').change(function(){

                /*3.24选择药品显示修改  所属仓库改变后清除记录列表所有产品的id的变量;所属仓库改变后搜索框中内容清空*/
                ps = Array();
                $('.keyword').val('');
                $('#word').html('');

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
                    var departName = $('#c-depart_id option:selected').text();
                    $('#depotName').html('仓库：'+depotName);
                    $('#departName').html('领药科室：'+departName);
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
		selectdrug: function(){
             Table.api.init({
                extend: {
                    // index_url: 'store/drugdraw/index',
                    // add_url: 'store/drugdraw/add',
                    // edit_url: 'store/drugdraw/edit',
                    // del_url: 'store/drugdraw/del',
                    // multi_url: 'store/drugdraw/multi',
                    // sel_url: 'store/drugdraw/selectdrug',
                    table: 'product',
                }        
            });
            var table = $("#table");
            var depot_id = $('#h-depot-id').val();
            // 初始化表格
            table.bootstrapTable({
                url: 'store/drugdraw/selectdrug?depot_id=' + depot_id,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'num', title: __('Med_num')},
                        {field: 'name', title: __('Med_name')},
                        {field: 'lotnum', title: '批号'},
                        {field: 'code', title: __('Med_code')},
                        {field: 'stock', title: __('Med_stock')}, 
                        {field: 'sizes', title: '规格'},
                        {field: 'unit', title: __('Med_unit')},
                        {field: 'price', title: __('Med_price')},
                        {field: 'cost', title: __('Med_cost')},
                        {field: 'prtime', title: '生产日期'},
                        {field: 'extime', title: '到期日期'},
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
                    
                    name: 'LIKE %...%',
                    code: 'LIKE %...%',
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
                        var rowHtml = '<tr class="clearNav"><td>' + '<input type="hidden" name="drugs_id[]" value="' + goodrow['id'] + '" />' + goodrow['name'] + '</td><td>' + goodrow['lotnum'] + '</td><td>' + goodrow['stock'] + '</td><td><input class="sto_num" type="text" value="1" name="storage_num[]" size="4"></td><td>' + goodrow['sizes'] + '</td><td>' + goodrow['unit'] + '</td><td><input class="cost" readonly type="text" value="' + goodrow['cost'] + '" name="cost[]"  size="4"></td><td><input type="text" readonly value="' + goodrow['cost'] + '" class="totalcost" name="totalcost[]"  size="4"></td><td><input type="text" value="' + goodrow['price'] + '" name="price[]"  size="4" readonly></td><td><input type="text" class="datetimepicker " data-date-format="YYYY-MM-DD" name="producttime[]" size="7" readonly  value="' + goodrow['prtime'] + '" /></td><td><input type="text" value="' + goodrow['extime'] + '" readonly class="datetimepicker " data-date-format="YYYY-MM-DD" name="expirestime[]"  size="7"></td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
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