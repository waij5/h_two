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

             

           /* $('#surePost').click(function(){                
                depot_id = $('#depot_id').val();
                dept_id = $('#dept_id').val();
                $('.allCusRow').prop("checked", false);
            });*/
            
            
            return Controller.renderList('ok');  

        },


        renderList:function(visitType){
            indexUrl = window.location.href;
            var currentOp = '';
            var currentFilter = '';

            Table.api.init({
                extend: {
                    index_url: 'wm/apparatus/index',
                    add_url: 'wm/apparatus/add',
                    edit_url: 'wm/apparatus/edit',
                    // multi_url: 'wm/apparatus/multi',

                    table: 'apparatus',
                }
            });

            var table = $("#table");
            if(visitType != ''){
                var rCols = [
                    [
                    	{field: 'operate', title: '', table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        {field: 'a_id', title: __('ID')},
                        {field: 'a_code', title: __('编号')},
                        {field: 'a_name', title: __('名称')},
                        {field: 'u_name', title: __('单位')},
                        {field: 'a_spec', title: __('规格')},
                        {field: 'depot_name', title: __('仓库')},
                        {field: 'a_join_stock', title: __('在库库存')},
                        {field: 'a_out_stock', title: __('报废库存')},
                        {field: 'a_createtime', title: __('创建日期'),formatter: Table.api.formatter.datetime},
                        
                    ]
                ];
            }


            table.bootstrapTable({
                commonSearch: false,
                search: false,
                pk: 'a_id',
                searchOnEnterKey: false,
                escape:false,
                // onClickRow:'false',
                onLoadSuccess: function(data){
                    // var pCusRow = $(".pCusRow");
                    // console.log(data);
                                  

                    

                    $("[data-toggle='tooltip']").tooltip();
                    if(data.total != 0){
                        if($('.tdDetail').length == 0){
                            var tdDetail = "<div class='tdDetail'><iframe  class='detailIframe'></iframe></div>"
                            $('.bootstrap-table').append(tdDetail);
                            $('.bootstrap-table .detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请单击 <strong style="color: #18bc9c">器械信息列表</strong> 显示明细</p></center>');

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
                    var autoClickIds= $('#table tbody tr:first').children('td').eq(1);
                    var autoClickId =autoClickIds.html();
                    // console.log(depot_id+'-*-*'+dept_id);

                    if(/^\d+$/.test(autoClickId)){
                        $('#table tbody tr').css('background-color','');
                        autoClickIds.parents('tr').css('background-color','#6ad4bf');
                        $('.detailIframe').attr('src', '/wm/apparatus/index_al/ids/' + autoClickId);
                        // console.log(autoClickId);
                    }

                    // $('#table').html('');


                },
                /*onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },*/
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'a_id',
                sortName: '',
                sortOrder: '',
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
                    'a_id': '=',
                    'a_code': '=',
                    'a_name': 'LIKE %...%',
                    'a_depot': '=',
                    'stime': '',
                    'etime': '',
                });
            });

            $('#table tbody').on('click', 'tr', function(){
                $('#table tbody tr').css('background-color','');
                // var i = $(this).data('index');
                // console.log(depot_id+'-*-*'+dept_id);
                var a_id = $(this).children('td').eq(1).html();
                $(this).css('background-color','#6ad4bf');
                // console.log(a_id);
                if(/^\d+$/.test(a_id)){
                    $('.detailIframe').attr('src', '/wm/apparatus/index_al/ids/' + a_id);
                
                }

                
            });

            
            
        },


        add: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();
        },
        edit: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();
        },

        index_al: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();

            $('#addAl').click(function(){
            	var alot_id = $('#apId').val();
            	var add_url='wm/apparatus/add_al';
                parent.window.Fast.api.open(add_url + (add_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + alot_id, '器械进库',{
                    callback:function(e){
                        if(e == '1'){
                            Toastr.success('添加成功！');
                            setTimeout(function(){
                                window.location.reload();
                            },950);                           
                        }else if(e == '2'){
                            Toastr.error('添加失败！') ;
                        }
                        // $('#cfEditRefresh').click();

                    }
                });

            });

            $('#scrapAl').click(function(){
            	var alot_id = $('#apId').val();
            	var scrap_url='wm/apparatus/scrap_al';
                parent.window.Fast.api.open(scrap_url + (scrap_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + alot_id, '器械报废',{
                    callback:function(e){
                        if(e == '1'){
                            Toastr.success('报废成功！');
                            setTimeout(function(){
                                window.location.reload();
                            },950);                           
                        }else if(e == '2'){
                            Toastr.error('报废失败！') ;
                        }
                        // $('#cfEditRefresh').click();

                    }
                });

            });



            //双击弹窗编辑
            $('#apparatusTable tbody').on('dblclick', 'tr', function() {
                $('#apparatusTable tbody tr').css('background-color','');
                // var i = $(this).data('index');
                var al_id = $(this).children('td').eq(1).html();
                $(this).css('background-color','#6ad4bf');
                // console.log(dr_id+'***'+status);
                if(/^\d+$/.test(al_id)){
                    var edit_url='wm/apparatus/edit_al';
                    parent.window.Fast.api.open(edit_url + (edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + al_id, '编辑',{
	                    callback:function(e){
	                        if(e == '1'){
	                            Toastr.success('编辑成功！');
	                            setTimeout(function(){
	                                window.location.reload();
	                            },950);                           
	                        }else if(e == '2'){
	                            Toastr.error('编辑失败！') ;
	                        }
	                        // $('#cfEditRefresh').click();

	                    }
	                });
                    
                }

                
            });
           
            
     
        },

        add_al: function(){
        	$('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            });

        	$('#alshop_time').datetimepicker(dateDrug);
        	$('#alstime').datetimepicker(dateDrug);
        	$('#aletime').datetimepicker(dateDrug);

            $('#sureAddAL').click(function(){
               
                var al_aid = $('#al_aid').val();
                var alotnum = $('#alotnum').val();
                var alcost =$('#alcost').val();
                var alnum =$('#alnum').val();
                var alstatus =$('#alstatus').val();
                var alsupplier =$('#alsupplier option:selected').val();
                var aluser =$('#aluser option:selected').val();
                var aldepart =$('#aldepart option:selected').val();
                var alremark =$('#alremark').val();
                var alshop_time  =$('#alshop_time').val();
                var alstime  =$('#alstime').val();
                var aletime =$('#aletime').val();
                // console.log(alshop_time);
                // console.log(lot_id+'**'+mpro_num+'**'+mcost+'**'+re_id+'**'+mprice);

                var baseUrl = '/wm/apparatus/add_al';

                var reg = /^[0-9]+(\.[0-9]{1,2})?$/;
                if(!alotnum){
                	Toastr.error('批号必填！');
                }
                else if(reg.test(alcost)==false || alcost<=0){
                	Toastr.error('价格必填且大于0！');
                }
                else if(/^\d+$/.test(alnum)==false || alnum<=0){
                	Toastr.error('数量必填且大于0！');
                }
                else{
                	$.ajax({
	                    type: 'POST',
	                    url: baseUrl,
	                    data: {al_aid:al_aid,alotnum:alotnum,alcost:alcost,alnum:alnum,alstatus:alstatus,alsupplier:alsupplier,aluser:aluser,aldepart:aldepart,alremark:alremark,alshop_time:alshop_time,alstime:alstime,aletime:aletime},
	                    dataType: 'json',
	                    success: function(msg){
	                        // var mss = eval(msg);
	                            if(msg == '1'){
	                                Fast.api.close('1');
	                            }else if(msg == '2'){
	                                Fast.api.close('2') ;
	                            }else{
	                                Toastr.error(msg['msg']);
	                            }
	                        
	                    }
	                })
                }
                

            });

        },

        scrap_al: function(){
        	var ps = new Array();
            $('#words').on('click', 'tr', function() {
                var alot_id = $(this).data('index');
                var type=1;//1、查询数据, 2、提交报废
                var url = '/wm/apparatus/scrap_al';
                $.ajax({
                    type: 'POST',
                        url: url,
                        data: {alot_id:alot_id,type:type},
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

                
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];
                // console.log(ps);

                $(this).parents('tr').remove();
            });

            Controller.api.bindevent();


            function msgReturn(msg){
                if(msg['alot_id']){
                    var dataId = msg['alot_id'];
                    // console.log(dataId);
                    $('.clearNav').each(function(){
                        pss = $(this).data('ps');
                        ps[pss] = pss;
                        
                    });

                    if(!ps[dataId]){
                        // alert('不存在重复');
                        var rowHtml = '<tr class="clearNav " id="ps_'+msg['alot_id']+'" data-ps="' + msg['alot_id'] + '" ><td>' + '<input type="hidden" name="alot_id[]" value="' + msg['alot_id'] + '" />' + msg['alot_id'] + '</td><td>' + msg['a_name'] + '</td><td>' + msg['alotnum'] + '</td><td><input class="alnum" type="text" value="1" name="alnum[]" size="5"></td><td>'+ msg['alusable_num']+ '</td><td>' + msg['aletime'] + '</td><td>' + msg['alcost'] + '</td><td>' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                        var appendEle = $(rowHtml);
                        $('#selectedDrugs').append(appendEle);

                    }
                };
                // console.log(ps);   
            };

            $('#sureScrap').click(function(){
            	var alot_idData = $("input[name='alot_id[]']");
                var alot_id = new Array();

                var alnumData = $("input[name='alnum[]']");
                var alnum = new Array();

                var type=2;//1、查询数据, 2、提交报废
                for (var i = 0; i < alot_idData.length; i++) {
                    alot_id[i] = alot_idData.eq(i).val();
                    alnum[i] = alnumData.eq(i).val();
                }

                var baseUrl = '/wm/apparatus/scrap_al';
                $.ajax({
                    type: 'POST',
                    url: baseUrl,
                    data: {alot_id:alot_id,alnum:alnum,type:type},
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

        edit_al: function () {
            $('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            }); 
            Controller.api.bindevent();
        },
       
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

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