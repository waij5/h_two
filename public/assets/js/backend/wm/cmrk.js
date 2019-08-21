define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker','bootstrap-select'], function ($, undefined, Backend, Table, Form,undefined,bootstrapSelect) {

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

            var conTentHeight = $(window).height() - 50;
            $('.contentTable').css('height', conTentHeight);
            //          $('.contentRight').css('height', conTentHeight);
            $(window).resize(function() {
                var contentTableHeight = $(window).height() - 50;
                $('.contentTable').css('height', contentTableHeight);
                var conTentHeight = $('.contentTable').height();
                var iframeHeight = parseInt($('.contentTable').css('height')) - $('.fixed-table-body').offset().top + 25;
                var tableBodyHeight = parseInt(conTentHeight) - $('.fixed-table-body').offset().top - 80;
                $('.fixed-table-body').css('height', tableBodyHeight);
                $('.tdDetail').css('height', iframeHeight);
            });

            

            return Controller.renderList('ok');

            // 初始化表格参数配置
            /*Table.api.init({
                extend: {
                    index_url: 'wm/goodsrk/index',
                    add_url: 'wm/goodsrk/add',
                    edit_url: 'wm/goodsrk/edit',
                    multi_url: 'wm/goodsrk/multi',

                    table: 'wm_manifest',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'man_id',
                sortName: 'man_id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'man_id', title: __('id')},
                        {field: 'man_num', title: __('man_num')},
                        {field: 'muid', title: __('muid')},
                        {field: 'sup_name', title: __('msupplier_id')},
                        {field: 'dpt_name', title: __('mdepot_id')},
                        {field: 'msecond_type', title: __('rk_type'), formatter: function (value, row, index) {
                        return __('rk_type ' + value)}},
                        {field: 'mcreatetime', title: __('mcreatetime'),formatter: Table.api.formatter.datetime},
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
                    man_id: '=',
                    man_num: '=',
                    mdepot_id: '=',
                    msupplier_id: '=',
                    stime: '',
                    etime: '',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            Table.api.bindevent(table);*/
        },

        renderList:function(visitType){
            indexUrl = window.location.href;
            var currentOp = '';
            var currentFilter = '';

            Table.api.init({
                extend: {
                    index_url: 'wm/cmrk/index',
                    add_url: 'wm/cmrk/add',
                    edit_url: 'wm/cmrk/edits',
                    multi_url: 'wm/cmrk/multi',

                    table: 'wm_manifest',
                }
            });

            var table = $("#table");
            if(visitType != ''){
                var rCols = [
                    [
                        {field: 'man_id', title: __('id')},
                        {field: 'man_num', title: __('man_num')},
                        {field: 'muid', title: __('muid')},
                        {field: 'sup_name', title: __('msupplier_id')},
                        {field: 'dpt_name', title: __('mdepot_id')},
                        {field: 'msecond_type', title: __('rk_type'), formatter: function (value, row, index) {
                        return __('rk_type ' + value)}},
                        {field: 'mcreatetime', title: __('mcreatetime'),formatter: Table.api.formatter.datetime},
                    ]
                ];
            }


            table.bootstrapTable({
                commonSearch: false,
                search: false,
                pk: 'man_id',
                searchOnEnterKey: false,

                onLoadSuccess: function(data){
                    $("[data-toggle='tooltip']").tooltip();
                    if(data.total != 0){
                        if($('.tdDetail').length == 0){
                            var tdDetail = "<div class='tdDetail'><iframe  class='detailIframe'></iframe></div>"
                            $('.bootstrap-table').append(tdDetail);
                            $('.bootstrap-table .detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请双击 <strong style="color: #18bc9c">单号</strong> 显示明细</p></center>');

                            var contentTableHeight = $(window).height() - 25;
                            $('.contentTable').css('height', contentTableHeight);
                            var iframeHeight = $(window).height() - 75;
                            var tableBodyHeight = $(window).height() - 185;
                            $('.fixed-table-body').css('height', tableBodyHeight);
                            if($('.fixed-table-container').width() >= 760) {
                                $('.fixed-table-body').css('height', tableBodyHeight + 30);
                            }

                            $('.fixed-table-container').css('width', '40%').css('float', 'left');
                            $('.tdDetail').css('width', '60%').css('float', 'left');
                            $('.tdDetail').css('height', iframeHeight);

                        }
                    }
                    // var clickId = $('.clickId0').text();
                    var autoClickIds= $('#table tbody tr:first').children('td').eq(0);
                    var autoClickId =autoClickIds.html();
                    // console.log(clickId);
                    if(/^\d+$/.test(autoClickId)){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/cmrk/edits/ids/' + autoClickId);
                    }else{
                        $('.bootstrap-table').html(tdDetail);
                        $('.bootstrap-table .detailIframe').contents().find('body').html('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请双击 <strong style="color: #18bc9c">单号</strong> 显示明细</p></center>');
                    }
                },
                /*onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },*/
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'man_id',
                sortName: 'man_id',
                sortOrder: 'DESC',
                /*buttons: [
                    { name: 'deny', icon: 'fa fa-pencil', classname: 'btn btn-xs btn-success btn-editone' },
                ],*/
                columns: rCols,

            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    man_id: '=',
                    man_num: '=',
                    mdepot_id: '=',
                    msupplier_id: '=',
                    stime: '',
                    etime: '',
                });
            });

            $('#table tbody').on('click', 'tr', function() {
                $('#table tbody tr').css('background-color','');
                // var i = $(this).data('index');
                var man_id = $(this).children('td').eq(0).html();
                $(this).css('background-color','#6ad4bf');
                if(/^\d+$/.test(man_id)){
                    $('.detailIframe').attr('src', '/wm/cmrk/edits/ids/' + man_id);
                // console.log($('.clickId0').data('clinckid'));
                }
                
            });

            
        },

        add: function () {
            Controller.api.bindevent();

             $('#selectedDrugs').on('input', '.mpro_num', function() {
				var type = $(this).attr('class');
				var tr = $(this).parents('tr');
				var data = $(this).val();
				updateRowData(type, tr, data);
                getTotal();
				
			});
             $('#selectedDrugs').on('input', '.mcost', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                getTotal();
            });

             $('#selectedDrugs').on('input', '.mprice', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                getTotal();
            });
             
            $('#selectedDrugs').on('input', '.mallcost', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                getTotal();
                
            });

            $('.getNum').click(function(){
                var num = 1;
                var url = '/wm/cmrk/getNum';
                // alert(num);
                $.ajax({
                        type: 'POST',
                        url: url,
                        data: {num:num},
                        dataType: 'json',
                        success: function(msg){
                            // alert(msg);
                            // var mss = eval(msg);
                            $('#c-man_num').attr('value', msg);
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
        $('#word').on('click', 'tr', function() {
            var i = $(this).data('index');
            selectProduct(i);
            getTotal();
        });
        
        function getTotal(){
            var totalNum = 0;
            var totalCost = 0;
            var totalPrice = 0;
            $('.clearNav').each(function(){
                totalNum += parseInt($(this).find('[name="mpro_num[]"]').val());
                totalCost += parseFloat($(this).find('[name="mallcost[]"]').val());
                totalPrice += parseFloat($(this).find('[name="mallprice[]"]').val());
            });
            // console.log(totalNum+'--'+totalCost+'--'+totalPrice);
            $('.totalNum').text(totalNum);
            $('.totalCost').text(totalCost.toFixed(4));
            $('.totalPrice').text(totalPrice.toFixed(2));
        }

        function productSearch(keywords){
            $('#word').removeClass('hidden');
            $('#word').empty().show();
            
            if(keywords == '') {
                $('#word').hide();
                return
            };
            var depot = $('#c-mdepot_id').val();

            // $('#c-remark').attr('value', keywords+depot);
            if(keywords && depot){
                var url = '/wm/cmjh/proSearch';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {keywords:keywords, depot:depot},
                    dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                    beforeSend: function() {

                        $('#word').append('<tr class="onloading"><td align="center" colspan="4" style="background-color:#ddddddd6;">-- -- -- -- -- -- -- -- -- -- -- 正在加载 -- -- -- -- -- -- -- -- -- -- --</td></tr>');
                    },
                    success: function(msg){
                        $('#word').html('');
                        $('#word').append('<tr><th width="23%">拼音码</th><th width="40%">名称</th><th width="22%">规格</th><th width="15%">单位</th></tr>');
                        // alert(msg);
                        dataObj = msg;
                        // var id = '';
                        $.each(dataObj, function(index,item){
                            $('#word').append('<tr class="tdcenter" onmouseover="$(this).css(\'background-color\',\'#18bc9c\')" onmouseout="$(this).css(\'background-color\',\'\')" style="word-wrap:break-word"   data-index="' + item.pro_id + '")"><td align="center">' + item.pro_spell + '</td><td align="center">' + item.pro_name + '</td><td align="center">' + item.pro_spec + '</td><td align="center">' + item.name + '</td></tr>');
                        })
                        
                        $('#word').removeClass('hidden');
                        $('#word').show();
                        $('#word').append('<tr><td align="center" colspan="4" style="background-color:#ddddddd6;">-- -- -- -- -- -- -- -- -- -- -- 已全部加载 -- -- -- -- -- -- -- -- -- -- --</td></tr>');
                    }
                })
            }
        }
            

        function selectProduct(i){
            if(i){
                var dataId = dataObj[i]['pro_id'];
            // var trList = parseFloat($(".clearNav").data('ps'));
                $('.clearNav').each(function(){
                    pss = $(this).data('ps');
                    ps[pss] = pss;
                    
                });

                if(!ps[dataId]){

                    // 页面滑动到最底部
                    window.scrollTo(0, document.documentElement.clientHeight);
                    
                    // alert('不存在重复');
                    var rowHtml = '<tr class="clearNav " id="ps_'+dataObj[i]['pro_id']+'" data-ps="' + dataObj[i]['pro_id'] + '" ><td>' + '<input type="hidden" name="lpro_id[]" value="' + dataObj[i]['pro_id'] + '" />' + dataObj[i]['pro_name'] + '</td><td><input name="lotnum[]" size="11"></td><td><input class="mpro_num" type="text" value="1" name="mpro_num[]" size="6"></td><td>' + dataObj[i]['pro_spec'] + '</td><td>' + dataObj[i]['name'] + '</td><td><input class="mcost"  type="text" value="' + dataObj[i]['pro_cost'] + '" name="mcost[]"  size="4"></td><td><input type="text"  value="' + dataObj[i]['pro_cost'] + '" class="mallcost" name="mallcost[]"  size="6"></td><td><input type="text" value="" class="datetimepicker"  data-date-format="YYYY-MM-DD" name="letime[]"  size="7"></td><td><input type="text" value="' + dataObj[i]['pro_amount'] + '" name="mprice[]" class="mprice" size="4"></td><td><input type="text" readonly value="' + dataObj[i]['pro_amount'] + '" class="mallprice" name="mallprice[]"  size="6"></td><td><input type="text" value="" class="datetimepicker"  data-date-format="YYYY-MM-DD" name="lstime[]" size="7"></td><td><input class="lproducer" type="text" value="' + dataObj[i]['producer'] + '" name="lproducer[]" size="8"></td><td><input class="laddr" type="text" value="' + dataObj[i]['addr'] + '" name="laddr[]" size="6"></td><td><input class="lapprov_num" type="text" value="' + dataObj[i]['approv_num'] + '" name="lapprov_num[]" size="6"></td><td><input class="lregist_num" type="text" value="' + dataObj[i]['regist_num'] + '" name="lregist_num[]" size="6"></td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                    var appendEle = $(rowHtml);
                    $('#selectedDrugs').append(appendEle);
//                  $(appendEle).find('.datetimepicker').parent().css('position', 'relative');   //日期插件
                    // $('.datetimepicker').css('z-index', '99999');
                    $(appendEle).find('.datetimepicker').datetimepicker(dateDrug);

                }
            };
            // console.log(ps);   
        }




            $('#sure').on('click', function() {
                $("#c-type").removeAttr("disabled");

            });
            
        
            $('#c-mdepot_id').change(function(){

                /*3.23选择药品显示修改  所属仓库改变后清除记录列表所有产品的id的变量;且搜索框中内容清空*/
                ps = Array();
                $('#word').html('');
                $('.keyword').val('');

                $('.clearNav').remove();
                getTotal();
                
            })

            $('#selectedDrugs').on('click', '[name="drugRemoveBtn"]', function() {

                /*3.23选择药品显示修改  药品列表删除单个药品时清除该记录id*/
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];
                // console.log(ps);

                $(this).parents('tr').remove();
                getTotal();
            });
           
            $('#clear').on('click', function() {

                /*3.23选择药品显示修改  药品列表清空时清除记录列表所有产品的id的变量*/
                ps = Array();

                $('.clearNav').remove();
                getTotal();
            });




            
        },
        edits: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();

            $('#editRemark').click(function(){
                if(confirm('是否确认对本入库单进行修改?')){
                    // $('.getNum').click(function(){
                        var manid = $('#c-man_id').val();
                        var mremark = $('#c-mremark').val();
                        var msupplier_id = $('#c-msupplier_id').val();
                        var url = '/wm/cmjh/editRemark';    //进货、入库、冲减、科室领料通用方法
                        // alert(mremark);
                        $.ajax({
                                type: 'POST',
                                url: url,
                                data: {manid:manid,mremark:mremark,msupplier_id:msupplier_id},
                                dataType: 'json',
                                success: function(msg){
                                    // console.log(msg);
                                    if(msg == '1'){
                                        Toastr.success('修改成功！');
                                    }else if(msg == '2'){
                                        Toastr.error('修改失败！')
                                    }else if(msg == '3'){
                                        Toastr.error('无效传值，修改失败！');
                                    }
                                    setTimeout(function(){
                                        window.location.reload();
                                    },2*900);
                                }
                            })
                    // })

                }
            });

            $('#alterJhDate').click(function(){
                if(confirm('是否确认本入货单进行反日期操作?')){
                    // $('.getNum').click(function(){
                        var alter_manid = $('#c-man_id').val();
                        var url = '/wm/drugs/alterDate';    //进货、入库通用方法
                        // alert(alter_manid);
                        $.ajax({
                                type: 'POST',
                                url: url,
                                data: {alter_manid:alter_manid},
                                dataType: 'json',
                                success: function(msg){
                                    // alert(msg);
                                    if(msg == '1'){
                                        Toastr.success('反日期成功！');
                                    }else if(msg == '2'){
                                        Toastr.error('反日期失败！')
                                    }else if(msg == '3'){
                                        Toastr.error('无效传值，反日期失败！');
                                    }
                                    setTimeout(function(){
                                        window.location.reload();
                                    },2*1000);
                                }
                            })
                    // })

                }else{
                    return false;
                }
            });


            //4.10  打印 打印功能
            $('#isPrint').click(function(){

                

                // $('.printRemove').remove();    //指定打印内容中多余部分移除
                if(confirm('是否开始打印?')){

                    

                    var depotName = $('#c-mdepot_id option:selected').text();
                    var producerName = $('#c-msupplier_id option:selected').text();
                    $('#depotName').html('仓库：'+depotName);
                    $('#supplierName').html('供应商：'+producerName);
                    $('.printA').css('display','');
                    $('.printB').css('display','none');
                    $('#selectedDrugs').css('width','100%');
                    $('.wpmcWidth').css('width','20%');
                    $('.phWidth').css('width','12%');
                    $('.rkWidth').css('width','10%');
                    $('.ggWidth').css('width','12%');
                    $('.dwWidth').css('width','6%');
                    $('.jjWidth').css('width','10%');
                    $('.zjjWidth').css('width','10%');
                    $('.scWidth').css('width','10%');
                    $('.dqWidth').css('width','10%');


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
    
    // function removeSelectedDrug(ele) {
    //     $(ele).parents('tr').remove();
    // }
	function updateRowData(type, tr, data) {
		var mpro_num;
		var mcost;
        var mprice;
		var total_cost;
        var total_price;
		if(type.indexOf("mpro_num") >= 0) {
            mpro_num = parseInt(data);
            mcost = parseFloat($(tr).find('[name="mcost[]"]').val());
            total_cost = (mpro_num * mcost).toFixed(4);
            $(tr).find('[name="mallcost[]"]').val(total_cost);

            mprice = parseFloat($(tr).find('[name="mprice[]"]').val());
            total_price = (mpro_num * mprice).toFixed(2);
            $(tr).find('[name="mallprice[]"]').val(total_price);

        } else if(type.indexOf("mallcost") >= 0){
            total_cost = parseFloat(data);
            mpro_num = parseInt($(tr).find('[name="mpro_num[]"]').val());
            mcost = (total_cost / mpro_num).toFixed(4);
            $(tr).find('[name="mcost[]"]').val(mcost);

        } else if(type.indexOf("mcost") >= 0){
            mcost = parseFloat(data);
            mpro_num = parseInt($(tr).find('[name="mpro_num[]"]').val());
            total_cost = (mpro_num * mcost).toFixed(4);
            $(tr).find('[name="mallcost[]"]').val(total_cost);

        } else if(type.indexOf("mprice") >= 0){
            mprice = parseFloat(data);
            mpro_num = parseInt($(tr).find('[name="mpro_num[]"]').val());
            total_price = (mpro_num * mprice).toFixed(2);
            $(tr).find('[name="mallprice[]"]').val(total_price);
        }
        
		
	}


    var dateDrug =    //日期插件
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