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
                    index_url: 'wm/drugscj/index',
                    add_url: 'wm/drugscj/add',
                    edit_url: 'wm/drugscj/edit',
                    multi_url: 'wm/drugscj/multi',

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
                        {field: 'msecond_type', title: __('cj_type'), formatter: function (value, row, index) {
                        return __('cj_type ' + value)}},
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
                    index_url: 'wm/drugscj/index',
                    add_url: 'wm/drugscj/add',
                    edit_url: 'wm/drugscj/edits',
                    multi_url: 'wm/drugscj/multi',

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
                        {field: 'msecond_type', title: __('cj_type'), formatter: function (value, row, index) {
                        return __('cj_type ' + value)}},
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
                        $('.detailIframe').attr('src', '/wm/drugscj/edits/ids/' + autoClickId);
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
                    $('.detailIframe').attr('src', '/wm/drugscj/edits/ids/' + man_id);
                // console.log($('.clickId0').data('clinckid'));
                }
                
            });

            
        },
        edits: function () {

            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            
            Controller.api.bindevent();

            $('#editRemark').click(function(){
                if(confirm('是否确认对本冲减单进行修改?')){
                    // $('.getNum').click(function(){
                        var manid = $('#c-man_id').val();
                        var mremark = $('#c-mremark').val();
                        var msupplier_id = $('#c-msupplier_id').val();
                        var url = '/wm/drugsjh/editRemark';    //进货、入库、冲减、科室领药通用方法
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
                    $('.ypmcWidth').css('width','20%');
                    $('.phWidth').css('width','12%');
                    $('.cjslWidth').css('width','10%');
                    $('.ggWidth').css('width','12%');
                    $('.dwWidth').css('width','6%');
                    $('.jjWidth').css('width','10%');
                    $('.zjjWidth').css('width','10%');
                    $('.dqWidth').css('width','10%');
                    $('.scWidth').css('width','10%');
                    


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
        add: function () {
            Controller.api.bindevent();

             $('#selectedDrugs').on('input', '.mpro_num', function() {
				var type = $(this).attr('class');
				var tr = $(this).parents('tr');
				var data = $(this).val();
				updateRowData(type, tr, data);
				
			});
             $('#selectedDrugs').on('input', '.mcost', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                
            });

             $('#selectedDrugs').on('input', '.mprice', function() {
                var type = $(this).attr('class');
                var tr = $(this).parents('tr');
                var data = $(this).val();
                updateRowData(type, tr, data);
                
            });

            $('.getNum').click(function(){
                var num = 1;
                var url = '/wm/drugscj/getNum';
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
            var i = $(this).index();        //获取产品列数，后台返回数据让键值从1开始
            selectProduct(i);
        });
        

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
                var url = '/wm/drugscj/proSearch';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {keywords:keywords, depot:depot},
                    dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                    beforeSend: function() {

                        // $('#word').append('<li class="onloading">正在加载。。。</li>');
                        $('#word').append('<tr class="onloading"><td align="center" colspan="8" style="background-color:#ddddddd6;">-- -- -- -- -- -- -- -- -- -- -- 正在加载 -- -- -- -- -- -- -- -- -- -- --</td></tr>');
                    },
                    success: function(msg){
                        $('#word').html('');
                        // $('#word').append('<li class="onloading" style="color:#18bc9c">药品编号<<>>名称<<>>批号<<>>进价<<>>可用库存<<>>单位<<>>规格<<>>到期日期</li>');
                        $('#word').append('<tr><th width="12%">药品编号</th><th width="27%">名称</th><th width="12%">批号</th><th width="10%">进价</th><th width="10%">可用库存</th><th width="8%">单位</th><th width="11%">规格</th><th width="12%">到期日期</th></tr>');
                        // alert(msg);
                        dataObj = msg;
                        // var id = '';
                        $.each(dataObj, function(index,item){
                            // $('#word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + item.lot_id + '">' + item.pro_code + '< >'+ item.pro_name + '< >'+ item.lotnum +'< >'+item.lcost +'< >'+ item.lstock +'< >'+  item.uname +'< >'+ item.pro_spec +'< >'+ item.letime +'</li>');
                            $('#word').append('<tr class="tdcenter" onmouseover="$(this).css(\'background-color\',\'#18bc9c\')" onmouseout="$(this).css(\'background-color\',\'\')" style="word-wrap:break-word"   data-index="' + item.lot_id + '")"><td align="center">' + item.pro_code + '</td><td align="center">' + item.pro_name + '</td><td align="center">' + item.lotnum + '</td><td align="center">' + item.lcost + '</td><td align="center">' + item.lstock + '</td><td align="center">' + item.uname + '</td><td align="center">' + item.pro_spec + '</td><td align="center">' + item.letime + '</td></tr>');
                        })
                        
                        $('#word').removeClass('hidden');
                        $('#word').show();
                        $('#word').append('<tr><td align="center" colspan="8" style="background-color:#ddddddd6;">-- -- -- -- -- -- -- -- -- -- -- 已全部加载 -- -- -- -- -- -- -- -- -- -- --</td></tr>');
                    }
                })
            }
        }
            

        function selectProduct(i){
            if(i){
                
                // console.log(i);
                if(dataObj[i]){
                    var dataId = dataObj[i]['lot_id'];
                    $('.clearNav').each(function(){
                        pss = $(this).data('ps');
                        ps[pss] = pss;
                        
                    });

                    if(!ps[dataId]){
                        // alert('不存在重复');
                        var rowHtml = '<tr class="clearNav " id="ps_'+dataObj[i]['lot_id']+'" data-ps="' + dataObj[i]['lot_id'] + '" ><td>' + '<input type="hidden" name="lot_id[]" value="' + dataObj[i]['lot_id'] + '" />' + dataObj[i]['pro_name'] + '</td><td><input name="lotnum[]"  class="lotnum" readonly size="9"  value="' + dataObj[i]['lotnum'] + '"></td><td>'+ dataObj[i]['lstock'] +'</td><td><input class="mpro_num" type="text" value="1" name="mpro_num[]" size="5"></td><td>' + dataObj[i]['pro_spec'] + '</td><td>' + dataObj[i]['uname'] + '</td><td><input class="mcost" readonly  type="text" value="' + dataObj[i]['lcost'] + '" name="mcost[]"  size="4"></td><td><input type="text" readonly value="' + dataObj[i]['lcost'] + '" class="mallcost" name="mallcost[]"  size="6"></td><input type="hidden" readonly value="' + dataObj[i]['lprice'] + '" name="mprice[]" class="mprice" size="4"><input type="hidden" readonly value="' + dataObj[i]['lprice'] + '" class="mallprice" name="mallprice[]"  size="6"><td><input type="text" value="' + dataObj[i]['letime'] + '" class="datetimepicker"  data-date-format="YYYY-MM-DD" name="metime[]" readonly  size="7"></td><td><input type="text" value="' + dataObj[i]['lstime'] + '" class="datetimepicker"  data-date-format="YYYY-MM-DD" name="mstime[]" readonly size="7"></td><td>' + dataObj[i]['lproducer'] + '</td><td>' + dataObj[i]['laddr'] + '</td><td>' + dataObj[i]['lapprov_num'] + '</td><td>' + dataObj[i]['lregist_num'] + '</td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                        var appendEle = $(rowHtml);
                        $('#selectedDrugs').append(appendEle);
                        // $(appendEle).find('.datetimepicker').parent().css('position', 'relative');   //日期插件
                        // $('.datetimepicker').css('z-index', '99999');
                        // $(appendEle).find('.datetimepicker').datetimepicker(dateDrug);

                    }
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

                    
                    var depotName = $('#c-mdepot_id option:selected').text();
                    var producerName = $('#c-msupplier_id option:selected').text();
                    $('#depotName').html('仓库：'+depotName);
                    $('#supplierName').html('供应商：'+producerName);
                    $('.printA').css('display','');
                    $('.printB').css('display','none');
                    $('#selectedDrugs').css('width','100%');
                    $('.ypmcWidth').css('width','20%');
                    $('.phWidth').css('width','12%');
                    $('.cjslWidth').css('width','10%');
                    $('.ggWidth').css('width','12%');
                    $('.dwWidth').css('width','6%');
                    $('.jjWidth').css('width','10%');
                    $('.zjjWidth').css('width','10%');
                    $('.dqWidth').css('width','10%');
                    $('.scWidth').css('width','10%');
                    


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