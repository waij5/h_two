define(['jquery', 'bootstrap', 'backend', 'table', 'jquery-freezeheader','form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table,undefined, Form, undefined) {
//日期插件 'bootstrap-datetimepicker'     undefined
    var Controller = {
    	index: function () {
           	$(document).ready(function() {
				fixTableHNdF();
				ajustFootWidth();
				$(window).resize(function() {
					var calcHeight = $(window).height() - $('#consumTable').offset().top - 60;
					var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200) + 'px';
					$('#consumTable').css('max-height', tbodyHeight);
					var table = $('#consumTable .fixTable');
					ajustWidth(table);
				});
			})
           	

           	$('.categorySort').click(function(){
           		$('.newsort').val('1');
           		$(".dislocationRight").click();
           	});

           	$('.nameSort').click(function(){
           		$('.newsort').val('2');
           		$(".dislocationRight").click();
           	});


           	function ajustFootWidth(){
           		var _td = $('#table tbody tr td')
                    var totalWidth = 0;
                    for(var i=2;i<10;i++){
                    	totalWidth+=parseInt($(_td).eq(i).css('width'))
                    }
                    $('#table tfoot th').eq(0).css('width',parseInt($(_td).eq(0).css('width'))+parseInt($(_td).eq(1).css('width')));
                    $('#table tfoot th').eq(1).css('width',totalWidth);
                    $('#table tfoot th').eq(2).css('width',parseInt($(_td).eq(10).css('width')));
                    $('#table tfoot th').eq(3).css('width',parseInt($(_td).eq(11).css('width')));
                    $('#table tfoot th').eq(4).css('width',parseInt($(_td).eq(12).css('width')));
                    $('#table tfoot th').eq(4).css('width',parseInt($(_td).eq(12).css('width')));
                    $('#table tfoot th').eq(5).css('width',parseInt($(_td).eq(13).css('width')));
                    $('#table tfoot th').eq(6).css('width',parseInt($(_td).eq(14).css('width')));
                var width = parseInt($('#table thead').width())+1;
                var pro_footTop = parseInt($('#consumTable').offset().top+$('#consumTable').height())-18;
				$('.proreport-tfoot').css('top',pro_footTop).css('width',width).css('margin-left','-1px');
           	}
                     	
			function ajustWidth(table) {
				//				var table = $('#consumTable .fixTable')
				var allTr = $('#consumTable #table thead tr');
				for(var j = 0; j < allTr.length; j++) {
					var preTr = $(allTr).eq(j)
					$('#table thead tr').eq(j).each(function(index) {
						var thLength = $('#table thead tr').eq(j).find('th').length;
						var singleWidth = 0;
						for(i = 0; i < thLength; i++) {
							singleWidth = parseInt($('#table thead tr').eq(j).find('th').eq(i).css('width'))
							$(table).find('thead tr').eq(j).find('th').eq(i).css('width', singleWidth).css('min-width', singleWidth);
						}
					})
				}
				ajustFootWidth();
				return table;
			};
			
            function fixTableHNdF() {
               var calcHeight = $(window).height() - $('#consumTable').offset().top - 60;
				var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200) + 'px';
				//              $('#table').freezeHeader({ 'height': tbodyHeight }); 
				$('#consumTable').css('max-height', tbodyHeight);
				var fixTable = $('#consumTable .fixTable');

				$('#consumTable').scroll(function() {
					var _this = this;
					var id = '#' + this.id;
					var width = $('#table thead').width();
					var top = $('#consumTable').offset().top;
					style = {
						'position': 'fixed',
						'top': top,
						'width': width
					};
					var scrollTop = $(_this).scrollTop() || $(_this).get(0).scrollTop;

					if(fixTable.length) {
						var table = $('.fixTable');
						var thLength = $('#consumTable #table thead tr th').length;
						for(var i = 0; i < thLength; i++) {
							beforeWidth = $('#consumTable #table thead tr th').eq(i).css('width');
							afterWidth = $('#consumTable .fixTable thead tr th').eq(i).css('width');
							if(beforeWidth != afterWidth) {
								ajustWidth(table);
								//								Controller.api.ajustWidth(table);
								break;
							}
						}
						(scrollTop === 0) ? fixTable.addClass('hidden'): fixTable.removeClass('hidden');
						fixTable.css(style);
					} else {
						var html = $('#consumTable .scrolltable thead').get(0).innerHTML;
						var table = $('<table class="table table-striped table-bordered table-hover scrolltable fixTable stockbalance_thead"><thead>' + html + '</thead></table>');
						table.css(style);
						ajustWidth(table);
						$(id).append($(table));
						fixTable = $(this).find('.fixTable');
					}
				});
                
                   
                
            }

            function countWidth(a,b,e){
                for(i=a;i<b;i++){
                        e += parseInt($('#table tr td').eq(i).css('width'));
                    }
            }
    

            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            // Table.api.bindevent(table);
            $('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });

        $('#btn-export').on('click', function() {
                var yjyWhere = $('#h_yjy_where').text();
                var url = '/wmreport/newss/downloadprocess?yjyWhere=' + encodeURI(yjyWhere);
                Fast.api.open(url, __('Downloading page'));
            });


        },


        downloadprocess: function() {
            var yjyWhere = Fast.api.query('where', window.location.href);
            return Backend.api.commondownloadprocess('/wmreport/newss/downloadprocess');
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