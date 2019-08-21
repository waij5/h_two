define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker','bootstrap-select'], function ($, undefined, Backend, Table, Form,undefined,bootstrapSelect) {
    var depot_id = '';
    var dept_id = '';
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

             

            $('#surePost').click(function(){                
                depot_id = $('#depot_id').val();
                dept_id = $('#dept_id').val();
                $('.allCusRow').prop("checked", false);
            });
            
            
            return Controller.renderList('ok');  

        },


        renderList:function(visitType){
            indexUrl = window.location.href;
            var currentOp = '';
            var currentFilter = '';

            Table.api.init({
                extend: {
                    index_url: 'wm/goodscflist/index',
                    edit_url: 'wm/goodscflist/edit',
                    multi_url: 'wm/goodscflist/multi',

                    table: 'order_items',
                }
            });

            var table = $("#table");
            if(visitType != ''){
                var rCols = [
                    [

                        {field: 'iffy', title: '<input type="checkbox" class="allCusRow">',formatter: function(value, row, index) {
                            if(value==0){
                                return '';
                            }else{
                                return '<input type="checkbox" class="pCusRow" value="'+row.customer_id+'" >';
                            }
                        }},
                        //onclick="getCus('+row.customer_id+')"
                        /*{checkbox: true,
                            formatter: function (value, row, index) {
                                if (row.iffy == 0) {
                                    this.checkbox = false;
                                } else {
                                    this.checkbox = true;
                                }
                            }
                        },*/
                        {field: 'iffy', title: __('发料状态'),formatter: function(value, row, index) {
                            if(value==0){
                                return '已发料';
                            }else{
                                return '<b style="color:#e73caa">待发料</b>';
                            }
                        }},
                        {field: 'ctm_name', title: __('顾客')},
                        {field: 'customer_id', title: __('客户卡号')},
                        {field: 'prescriber', title: __('最新开单科室')},
                        {field: 'drtime', title: __('最新划扣日期'),formatter: Table.api.formatter.datetime},
                    ]
                ];
            }


            table.bootstrapTable({
                commonSearch: false,
                search: false,
                pk: 'customer_id',
                searchOnEnterKey: false,
                escape:false,
                // onClickRow:'false',
                onLoadSuccess: function(data){
                    // var pCusRow = $(".pCusRow");
                    // console.log(data);
                    $(".allCusRow").click(function() {
                        var pCusRow = $(".pCusRow");
                        // console.log(666);
                        for (var i = 0; i < pCusRow.length; ++i)
                        {
                            if (this.checked)
                            {
                                pCusRow[i].checked = true;
                            }
                            else
                            {
                                pCusRow[i].checked = false;
                            }

                        }
                    });
                    $(".pCusRow").on('click', function() {
                        // console.log(555);
                        var allCusRow = $(".allCusRow");
                        var pCusRow = $(".pCusRow");
                        var checkedStatus = true;
                        for (var i = 0; i < pCusRow.length; ++i)
                        {
                            checkedStatus = (checkedStatus && pCusRow[i].checked);
                            
                        }
                        if (checkedStatus)
                        {
                            // allCusRow.checked = true;
                            allCusRow.prop("checked", true);
                        }
                        else
                        {
                            // allCusRow.checked = false;
                            allCusRow.prop("checked", false);
                        }
                        
                    });
                    $(".pCusRow").click(function(event) {
                        event.stopPropagation();
                    });                    

                    

                    $("[data-toggle='tooltip']").tooltip();
                    if(data.total != 0){
                        if($('.tdDetail').length == 0){
                            var tdDetail = "<div class='tdDetail'><iframe  class='detailIframe'></iframe></div>"
                            $('.bootstrap-table').append(tdDetail);
                            $('.bootstrap-table .detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请单击 <strong style="color: #18bc9c">客户列</strong> 显示明细</p></center>');

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
                    var autoClickIds= $('#table tbody tr:first').children('td').eq(3);
                    var autoClickId =autoClickIds.html();
                    // console.log(depot_id+'-*-*'+dept_id);

                    if(/^\d+$/.test(autoClickId) && !depot_id && !dept_id){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + autoClickId);
                        // console.log(autoClickId);
                    }
                    else if(/^\d+$/.test(autoClickId) && depot_id && dept_id){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + autoClickId+'/depot_id/'+depot_id+'/dept_id/'+dept_id);
                    }
                    else if(/^\d+$/.test(autoClickId) && depot_id && !dept_id){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + autoClickId+'/depot_id/'+depot_id);
                    }
                    else if(/^\d+$/.test(autoClickId) && !depot_id && dept_id){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + autoClickId+'/dept_id/'+dept_id);
                    }
                    else if(!autoClickId){
                        $('.bootstrap-table').html(tdDetail);
                        $('.bootstrap-table .detailIframe').contents().find('body').html('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请单击 <strong style="color: #18bc9c">客户列</strong> 显示明细</p></center>');
                    }

                    // $('#table').html('');


                },
                /*onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },*/
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'customer_id',
                sortName: '',
                sortOrder: '',
                /*buttons: [
                    { name: 'deny', icon: 'fa fa-pencil', classname: 'btn btn-xs btn-success btn-editone' },
                ],*/
                columns: rCols,

            });
            $('.btn-refresh').click(function(){
                $('.allCusRow').prop("checked", false);
            });

            $('#isCusPrint').click(function(){
                // console.log('66666');
                var pCusRow = $(".pCusRow");
                var cStatuss = new Array();
                for (var i = 0; i < pCusRow.length; ++i)
                {
                    if(pCusRow[i].checked ===true && pCusRow[i].value!=''){
                       cStatuss[i] = pCusRow[i].value;
                       
                    }
                    
                };
                if (cStatuss.length >0)
                {
                    cStatuss=cStatuss.filter(d=>d);//去除假值c = c+1;
                    // console.log(cStatuss);
                    var all_print_url='wm/goodscflist/cf_print';

                    if(!depot_id && !dept_id){
                        top.window.Fast.api.open(all_print_url + (all_print_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + cStatuss+'/printType/'+'1', 'ALL物资打印');             
                    }
                    else if(depot_id && dept_id){
                        top.window.Fast.api.open(all_print_url + (all_print_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + cStatuss+'/printType/'+'1'+'/depot_id/'+depot_id+'/dept_id/'+dept_id, 'ALL物资打印');
                    }
                    else if(depot_id && !dept_id){
                        top.window.Fast.api.open(all_print_url + (all_print_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + cStatuss+'/printType/'+'1'+'/depot_id/'+depot_id, 'ALL物资打印');
                    }
                    else if(!depot_id && dept_id){
                        top.window.Fast.api.open(all_print_url + (all_print_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + cStatuss+'/printType/'+'1'+'/dept_id/'+dept_id, 'ALL物资打印');
                    }
                    // console.log(depot_id+'-*-*'+dept_id);
                }else{
                    Toastr.error('请选择打印项！');
                }

                
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
                    customer_id: '=',
                    'c.ctm_id': '=',
                    'pro.depot_id': '=',
                    'a.dept_id': '=',
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

            $('#table tbody').on('click', 'tr', function(){
                $('#table tbody tr').css('background-color','');
                // var i = $(this).data('index');
                // console.log(depot_id+'-*-*'+dept_id);
                var customer_id = $(this).children('td').eq(3).html();
                $(this).css('background-color','#6ad4bf');
                // console.log(customer_id);
                if(/^\d+$/.test(customer_id) && !depot_id && !dept_id){
                    $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + customer_id);
                
                }else if(/^\d+$/.test(customer_id) && depot_id && dept_id){
                    $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + customer_id+'/depot_id/'+depot_id+'/dept_id/'+dept_id);
                }
                else if(/^\d+$/.test(customer_id) && depot_id && !dept_id){
                    $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + customer_id+'/depot_id/'+depot_id);
                }
                else if(/^\d+$/.test(customer_id) && !depot_id && dept_id){
                    $('.detailIframe').attr('src', '/wm/goodscflist/edit/ids/' + customer_id+'/dept_id/'+dept_id);
                }

                
            });

            
            
        },




        edit: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();

            $('.depotData').change(function(){
                var depotData=$('.depotData option:selected').text();   
                var deptmentData=$('.deptmentData option:selected').text();               
                // console.log(depotData);
                // var selectedDrugs = $("#selectedDrugs tbody tr");
                var pRow = $(".pRow");
                var printTbody = $("#printTbody tr");

                for (var i = 0; i < printTbody.length; ++i)
                {
                    if (depotData == $("#printTbody tr").eq(i).children('td').eq(5).html() && deptmentData == $("#printTbody tr").eq(i).children('td').eq(6).html())
                    {
                        pRow[i].checked = true;
                        /*$("#printTbody tr").eq(i).children('td').eq(0).children('pRow').checked = true;
                        console.log($("#printTbody tr").eq(i).children('td').eq(0));*/
                    }
                    else if(depotData == $("#printTbody tr").eq(i).children('td').eq(5).html() && deptmentData=='')
                    {
                        pRow[i].checked = true;
                    }else if(deptmentData == $("#printTbody tr").eq(i).children('td').eq(6).html() && depotData=='')
                    {
                        pRow[i].checked = true;
                    }else{
                        pRow[i].checked = false;
                    }

                };
            });
            $('.deptmentData').change(function(){
                var depotData=$('.depotData option:selected').text();
                var deptmentData=$('.deptmentData option:selected').text();                
                // console.log(deptmentData);
                // var selectedDrugs = $("#selectedDrugs tbody tr");
                var pRow = $(".pRow");
                var printTbody = $("#printTbody tr");

                for (var i = 0; i < printTbody.length; ++i)
                {
                    if (depotData == $("#printTbody tr").eq(i).children('td').eq(5).html() && deptmentData == $("#printTbody tr").eq(i).children('td').eq(6).html())
                    {
                        pRow[i].checked = true;
                        /*$("#printTbody tr").eq(i).children('td').eq(0).children('pRow').checked = true;
                        console.log($("#printTbody tr").eq(i).children('td').eq(0));*/
                    }
                    else if(deptmentData == $("#printTbody tr").eq(i).children('td').eq(6).html() && depotData=='')
                    {
                        pRow[i].checked = true;
                    }else if(depotData == $("#printTbody tr").eq(i).children('td').eq(5).html() && deptmentData=='')
                    {
                        pRow[i].checked = true;
                    }else{
                        pRow[i].checked = false;
                    }

                };
            });

            //双击弹窗发料或撤料
            $('#selectedDrugs tbody').on('dblclick', 'tr', function() {
                $('#selectedDrugs tbody tr').css('background-color','');
                // var i = $(this).data('index');
                var dr_id = $(this).children('td').eq(2).html();
                var status = $(this).children('td').eq(1).html();
                $(this).css('background-color','#6ad4bf');
                // console.log(dr_id+'***'+status);
                if(/^\d+$/.test(dr_id) && status=='待发料'){
                    var edit_url='wm/goodscf/edit_one';
                    parent.window.Fast.api.open(edit_url + (edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + dr_id+(edit_url.match(/(\?|&)+/) ? "&type=" : "/type/") + 2, '发料'+' id '+ dr_id,{
                        callback:function(e){
                            if(e == '1'){
                                Toastr.success('发料成功！');
                                setTimeout(function(){
                                    window.location.reload();
                                },950);                        
                            }else if(e == '2'){
                                Toastr.error('发料失败！') ;
                            }
                            // $('#cfEditRefresh').click();

                        }
                    });
                }

                if(/^\d+$/.test(dr_id) && status=='已发料'){
                    var edit_url='wm/goodscf/edit_two';
                    parent.window.Fast.api.open(edit_url + (edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + dr_id+(edit_url.match(/(\?|&)+/) ? "&type=" : "/type/") + 2, '撤料'+' id '+ dr_id,{
                        callback:function(e){
                            if(e == '1'){
                                Toastr.success('撤销成功！');
                                setTimeout(function(){
                                    window.location.reload();
                                },950);                           
                            }else if(e == '2'){
                                Toastr.error('撤销失败！') ;
                            }
                            // $('#cfEditRefresh').click();

                        }
                    });
                    // Fast.api.close(data);
                }
                
            });

            
            /*function allChecked(allRow,pRow){
                console.log(22);
            };*/
            
            $("#allRow").on('click', function() {
                var pRow = $(".pRow");
                 // console.log(pRow);
                for (var i = 0; i < pRow.length; ++i)
                {
                    if (this.checked)
                    {
                        pRow[i].checked = true;
                    }
                    else
                    {
                        pRow[i].checked = false;
                    }

                }
            });
            $(".pRow").on('click', function() {
                var allRow = $("#allRow");
                var pRow = $(".pRow");
                var checkedStatus = true;
                for (var i = 0; i < pRow.length; ++i)
                {
                    checkedStatus = (checkedStatus && pRow[i].checked);
                    
                }
                if (checkedStatus)
                {
                    // allRow.checked = true;
                    allRow.prop("checked", true);
                }
                else
                {
                    // allRow.checked = false;
                    allRow.prop("checked", false);
                }
                
            });
            $(".pRow").dblclick(function(event) {
                event.stopPropagation();
            });


           
            //4.10  打印 打印功能
            $('#isPrint').click(function(){

                var pRow = $(".pRow");
                var cStatus = new Array();
                for (var i = 0; i < pRow.length; ++i)
                {
                    if(pRow[i].checked ===true && pRow[i].value!=''){
                       cStatus[i] = pRow[i].value;
                    }
                    
                }
                if (cStatus.length >0)
                {
                    cStatus=cStatus.filter(d=>d);//去除假值
                    // console.log(cStatus);
                    var cf_print_url='wm/goodscflist/cf_print';
                    top.window.Fast.api.open(cf_print_url + (cf_print_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + cStatus, '物资打印');
                    // console.log(pRow);
                    // allRow.prop("checked", true);
                }else{
                    Toastr.error('请选择打印项！')
                }

                
            });
     
        },

        cf_print:function(){
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();

            document.getElementById('surePrint').onclick=(function(){
                
                // console.log(44);
                bdhtml=window.document.body.innerHTML;
                    sprnstr="<!--startprint-->"; //开始打印标识字符串有17个字符
                    eprnstr="<!--endprint-->"; //结束打印标识字符串
                    prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); //从开始打印标识之后的内容
                    prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); //截取开始标识和结束标识之间的内容
                    window.document.body.innerHTML=prnhtml; //把需要打印的指定内容赋给body.innerHTML
                    window.print(); //调用浏览器的打印功能打印指定区域
            });
            
            
        },
       
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    
    //打印复选框
    

    function updateRowData(type, tr, data) {
        var mpro_num;
        var mcost;
        var mprice;
        var total_cost;
        var total_price;
        if(type.indexOf("mpro_num") >= 0) {
            mpro_num = parseInt(data);
            mcost = parseFloat($(tr).find('[name="mcost[]"]').val());
            total_cost = (mpro_num * mcost).toFixed(2);
            $(tr).find('[name="mallcost[]"]').val(total_cost);

            mprice = parseFloat($(tr).find('[name="mprice[]"]').val());
            total_price = (mpro_num * mprice).toFixed(2);
            $(tr).find('[name="mallprice[]"]').val(total_price);

        } else if(type.indexOf("mcost") >= 0){
            mcost = parseFloat(data);
            mpro_num = parseInt($(tr).find('[name="mpro_num[]"]').val());
            total_cost = (mpro_num * mcost).toFixed(2);
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