define(['jquery', 'bootstrap', 'backend', 'table', 'jquery-freezeheader', 'form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table,undefined, Form, undefined) {
//日期插件 'bootstrap-datetimepicker'     undefined
    var Controller = {
    	index: function () {
           		$(document).ready(function () {
                fixTableHNdF();

                $(window).resize(function () {
                    fixTableHNdF();
                });
            })

            function fixTableHNdF() {
            	
                var calcHeight = $(window).height() - parseInt($('#ribbon').css('height'))+parseInt($('#ribbon').css('padding'))-$('.proreportDate').offset().top-40;
                var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200) + 'px';
                $('#table').freezeHeader({ 'height': tbodyHeight });
				$('#table tfoot th').each(function(index) {
                 	
                 	var singleWidth = parseInt($('#table tr th').eq(index).css('width'));
                 	$('#table tfoot th').eq(index).css('width',parseInt($('#table tr th').eq(index).css('width')));
                 	var totalWidth = 0;
                 	for(i=2;i<8;i++){
                 		totalWidth += parseInt($('#table tr th').eq(i).css('width'))
                 	}
                 	
                 	$('#table tfoot th').eq(2).css('width',totalWidth);
                 	for(i=3;i<11;i++){
                 		$('#table tfoot th').eq(i).css('width',parseInt($('#table tr th').eq(i+5).css('width')));
                  	}
                 	var lastWidth = 0;
                 	for(i=15;i<18;i++){
                 		lastWidth += parseInt($('#table tr th').eq(i).css('width'))
                 	}
                 	$('#table tfoot th').eq(10).css('width',lastWidth);
                })
				var width = parseInt($('#table thead').width())+3;
				var pro_footTop;
//				var tableHeight = ($('#consumTable').height() >= $('#table').height() ? $('#table').height() : $('#consumTable').height());
                if($('#consumTable').height() >= $('#table').height()){
               		pro_footTop = parseInt($('#consumTable').offset().top+$('#table').height());
                }else{
                	pro_footTop = parseInt($('#consumTable').offset().top+$('#consumTable').height())-19;
                }
				$('.proreport-tfoot').css('top',pro_footTop).css('width',width).css('margin-left','-1px');
                
            }
      

            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            // Table.api.bindevent(table);
            $('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });

            var headerCells = $('.one_table td');
	        $('.two_table th').each(function(i,n)
	        {
	            $(this).css('width',headerCells.eq(i).css('width'));
	        });
	        //关联宽度
	        $(window).resize(function () {
	            $('.two_table').width($('.two_table > .body table').width());
	        }).triggerHandler('resize');



            $('#btn-export').on('click', function() {
                var yjyWhere = $('#h_yjy_where').text();
                var url = '/proreport/stockdetail/downloadprocess?yjyWhere=' + encodeURI(yjyWhere);
                Fast.api.open(url, __('Downloading page'));
            });

        },

        downloadprocess: function() {
            var yjyWhere = Fast.api.query('where', window.location.href);
            return Backend.api.commondownloadprocess('/proreport/stockdetail/downloadprocess');
        },


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