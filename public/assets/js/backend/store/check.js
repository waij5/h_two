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
                    index_url: 'store/check/index',
                    add_url: 'store/check/add',
                    edit_url: 'store/check/edit',
                    del_url: 'store/check/del',
                    multi_url: 'store/check/multi',
                    table: 'stock_checks',
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
                        {field: 'depot_id', title: __('Depot_id')},
                        {field: 'uid', title: __('Uid')},
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
                    id: '=',
                    order_num: '=',
                    depot_id: '=',
                    uid: '=',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();


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
                var url = '/store/check/proSearch';
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
                    var rowHtml = '<tr class="clearNav"  id="ps_'+dataObj[i]['id']+'" data-ps="' + dataObj[i]['id'] + '" ><td style="vertical-align:middle">' + '<input type="hidden" name="goods_id[]" value="' + dataObj[i]['id'] + '" />' + dataObj[i]['id'] + '</td><td style="vertical-align:middle">' + dataObj[i]['code'] + '</td><td style="vertical-align:middle">' + dataObj[i]['name'] + '</td><td class="col-xs-2 col-md-2"><input class="form-control" type="number" readonly="readonly" value="' + dataObj[i]['stock'] + '" name="stock[]" size="4"></td><td class="col-xs-2 col-md-2"><input  class="form-control" type="number" value="' + dataObj[i]['stock'] + '" name="storage_num[]" min="0"></td><td style="vertical-align:middle">' + dataObj[i]['unit'] + '</td><td style="vertical-align:middle">' + '<a href="javascript:;" name="drugRemoveBtn" class="btn btn-xs btn-danger btn-delone" title=""><i class="fa fa-trash"></i></a>' + '</td></tr>';
                    
                    $('#selectedProducts').append(rowHtml);
                    
                }
            };
            // console.log(ps);   
        }
			
            $('#c-depot_id').change(function(){

                /*3.27选择产品显示修改  所属仓库改变后清除记录列表所有产品的id的变量;且搜索框中内容清空*/
                ps = Array();
                $('#word').html('');
                $('.keyword').val('');

                $('.clearNav').remove();
            })
            
            $('#selectedProducts').on('click', '[name="drugRemoveBtn"]', function() {

                /*3.27选择产品显示修改  产品列表删除单个产品时清除该记录id*/
                var drugRemoveBtnPs = $(this).parents('tr').data('ps');
                delete ps[drugRemoveBtnPs];

                $(this).parents('tr').remove();
            });
           
            $('#clear').on('click', function() {

                /*3.27选择产品显示修改  产品列表清空时清除记录列表所有产品的id的变量*/
                ps = Array();

                $('.clearNav').remove();
            });
			
			
        },
        edit: function () {
            Controller.api.bindevent();
			

            $('#selectedProducts').on('click', '[name="drugRemoveBtn"]', function() {
                $(this).parents('tr').remove();
            });
           
            $('#clear').on('click', function() {
                $('#selectedProducts tr').remove();
            });

        },
		
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});