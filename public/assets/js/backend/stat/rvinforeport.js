define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-select'], function($, undefined, Backend, Table, Form,bootstrapSelect) {
    var Controller = {
        index: function() {
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
        	$(document).ready(function() {
				fixTableHNdF();
				$(window).resize(function() {
					var calcHeight = $(window).height() - $('#consumTable').offset().top - 80;
					var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200) + 'px';
					$('#consumTable').css('max-height', tbodyHeight);
					
					var table = $('#consumTable .fixTable');					
					ajustWidth(table);
				});
			})
            Form.events.datetimepicker($('#f-commonsearch'));
            $('#a-search-customer').on('click', function() {
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            });
            $('button[type="reset"]').on('click', function() {
                $('.nickname').val('');
                $('#field_admin_id').val('');
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            });
            Controller.api.bindStaffSelect();
 

            var postUrl = 'stat/rvinforeport/index';
            var op = {
                    'customer_id': '=',
                    'customer.ctm_id': '=',
                    'customer.ctm_type': '=',
                    'customer.ctm_mobile': 'LIKE %...%',
                    'customer.ctm_name': 'LIKE %...%',
                    'customer.ctm_first_dept_id': '=',
                    'admin_id': '=',
                    'resolve_admin_id': '=',
                    'rvt_type': '=',
                    'admin.dept_id': '=',
                    'customer.arrive_status': '=',
                    'customer.createtime': 'BETWEEN',
                    'rv_date': 'BETWEEN',
                    'rv_time': 'BETWEEN',
                    'rvi_content': 'LIKE %...%',
                    'customer.ctm_first_cpdt_id': '=',
                    'customer.ctm_first_tool_id': '=',
                    'customer.ctm_first_osc_cpdt_id': '=',
                    'customer.ctm_first_osc_dept_id': '=',
                };

            var pageLimit = 50;
            var pageTotal = 0;
            var currentPageNumber = 0;
            var currentFilter = '';

            $('#btn-submit-1').on('click', function() {
                var inputs = $('commonsearch-table fieldset input');
                var selects = $('commonsearch-table fieldset select');
                
                currentCustomerId = -1;
                currentPageNumber = 0;
                currentNo = 0;
                currentFilter = Backend.api.yjyGenerateParams($("form.form-commonsearch"), op);
                loadData();
            });

            $('#btn-export').on('click', function() {
                var url = '/stat/rvinforeport/downloadprocess?sort=customer_id&order=ASC' + '&op=' + encodeURI(JSON.stringify(op)) + '&filter=' + encodeURI(JSON.stringify(currentFilter));
                Fast.api.open(url, __('Downloading page'));
            });

            $('#div-load-more').on('click', '#btn-rec-load-more', function () {
                loadData();
            });

            function loadData(){
                var offset = currentPageNumber * pageLimit;
                var loadLayerIndex = Layer.load();

                $.ajax({
                    url: postUrl,
                    data: {
                        filter: JSON.stringify(currentFilter),
                        op: JSON.stringify(op),
                        sort: 'customer_id asc, rv_date',
                        order: 'ASC',
                        limit: pageLimit,
                        offset: offset,
                        onlyNoneRevisit: $('#onlyNoneRevisit').prop('checked'),
                    },
                    dataType: 'json',
                    success: function(res) {
                        Layer.close(loadLayerIndex);
                        if (currentPageNumber < 1) {
                            pageTotal = Math.ceil(parseInt(res.total) / pageLimit);
                            $('#table tbody').empty();

                            if (res.summary) {
                                for (var i in res.summary) {
                                    $('#h-' + i).text(res.summary[i]);
                                }
                            }
                        }
                        currentPageNumber += 1;
                        for (var i in res.rows) {
                            Controller.api.generateRow(res.rows[i], 'td');
                        }
                        $("#table tbody [data-toggle='tooltip']").tooltip();
                        if (pageTotal > currentPageNumber) {
                            if ($('#btn-rec-load-more').length == 0) {
                                $('#div-load-more').append('<a href="javascript:;" id="btn-rec-load-more">' + __('Load more...') + '</a>');
                            }
                            
                        } else {
                            $('#btn-rec-load-more').remove();
                        }
                    },
                    error: function(e) {
                        Layer.close(loadLayerIndex);
                        Layer.msg(__('Error occurs'), {
                            icon: 2
                        });
                    }
                });
            };
              function ajustWidth(table){
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
					return table;
			};
            
            function fixTableHNdF() {
				var calcHeight = $(window).height() - $('#consumTable').offset().top - 80;
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
						for(var i =0;i<thLength;i++){
							beforeWidth = $('#consumTable #table thead tr th').eq(i).css('width');
							afterWidth = $('#consumTable .fixTable thead tr th').eq(i).css('width');
							if(beforeWidth!=afterWidth){
								ajustWidth(table);
								break;
							}
						}
						(scrollTop === 0) ? fixTable.addClass('hidden'): fixTable.removeClass('hidden');
						fixTable.css(style);
					} else {
						var html = $('#consumTable .scrolltable thead').get(0).innerHTML;
						var table = $('<table class="table table-bordered table-condensed table-hover scrolltable fixTable"><thead>' + html + '</thead></table>');
						table.css(style);
						ajustWidth(table);
						$(id).append($(table));
						fixTable = $(this).find('.fixTable');
					}
				});
			}  
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('/stat/rvinforeport/downloadprocess');
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
            bindStaffSelect: function() {
                var tmpList = new Array();
                $('.nickname').keyup(function() {
                    var _this = $(this);
                    var keywords = $(this).val();
                    if (keywords == '') {
                        $('.word').empty().hide();
                        return;
                    };
                    var filter = JSON.stringify({
                        username: keywords
                    });
                    var op = JSON.stringify({
                        username: "LIKE %...%"
                    });
                    var fieldSpell = "username";
                    var username = "username";
                    $.ajax({
                        url: '/cash/order/staffquicksearch',
                        data: {
                            filter: filter,
                            op: op
                        },
                        dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                        beforeSend: function() {
                            $(_this).siblings().find('.word').append('<div>正在加载。。。</div>');
                        },
                        success: function(data) {
                            $(_this).siblings().find('.word').empty().show();
                            // console.log(data);
                            tmpList = data.rows;
                            if (data.total) {
                                for (var i in data.rows) {
                                    $(_this).siblings().find('.word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + i + '">' + data.rows[i]['nickname'] + '</li>');
                                }
                                $(_this).siblings().find('.word').show();
                            }
                        },
                        error: function() {
                            $(_this).siblings().find('.word').empty().show();
                            $(_this).siblings().find('.word').append('<div class="click_work">Fail "' + keywords + '"</div>');
                        }
                    })
                })
                $('.word').on('click', 'li', function() {
                    var i = $(this).data('index');
                    $('.nickname').val(tmpList[i]['nickname']);
                    $('#field_admin_id').val(tmpList[i]['id']);
                });
                $('#btn-admin-clear').on('click', function() {
                    $('.nickname').val('');
                    $('#field_admin_id').val('');
                });
            },
            generateRow: function (rowData, type) {
                console.log(rowData);
                console.log(type);
                var row = '';
                var rvPlan = rowData['rv_plan'] ? rowData['rv_plan'] : '';
                var rvTime = rowData['rv_time'] ? Table.api.formatter.datetime(rowData['rv_time']) : '';
                var rvCreatetime = rowData['createtime'] ? Table.api.formatter.datetime(rowData['createtime']) : '';
                var rvStatus = rowData['rv_status'] ? rowData['rv_status'] : '';
                var customerGender = typeof genders[rowData['ctm_sex']] != 'undefined' ? genders[rowData['ctm_sex']] : '';
                var fatName = rowData['fat_name'] ? rowData['fat_name'] : '';
                // var ctmNextRvinfo = rowData['ctm_next_rvinfo'] ? rowData['ctm_next_rvinfo'] : '';
                if (rowData['arrive_status'] == 1) {
                    var arriveStatus = '已上门';
                } else {
                    var arriveStatus = '未上门';
                }

                if (rowData['customer_id'] != currentCustomerId) {
                    row += '<tr style="background-color:#f9f9f9">' + 
                            '<td>' + (++ currentNo) + '</td>' +
                            '<td class="text-center">' +  (!rowData['ctm_name'] ? __('None') : rowData['ctm_name']) + '</td>' + 
                            '<td class="text-center">' + rowData['customer_id'] + '</td>' + 
                            '<td class="text-center">' + arriveStatus + '</td>' + 
                            '<td class="text-center">' + customerGender + '</td>' + 
                            '<td class="text-center">' + rowData['ctm_age'] + '</td>' + 
                            '<td class="text-center">' + rowData['ctm_mobile'] + '</td>' +
                            '<td class="text-center">' + rowData['ctm_first_cpdt_name'] + '</td>' +
                            '<td class="text-center">' + rowData['ctm_first_dept_name'] + '</td>' +
                            '<td class="text-center">' + rowData['ctm_first_osc_cpdt_name'] + '</td>' +
                            '<td class="text-center">' + rowData['ctm_first_osc_dept_name'] + '</td>' ;
                            // +'<td class="text-center">' + ctmNextRvinfo + '</td>';
                            if(rowData['ctm_next_rvinfo']) {
                                row += '<td class="text-center">' + rowData['ctm_next_rvinfo'] + '</td>';
                            } else {
                                row +=  '<td class="text-center"><span style="color: red; font-weight: bold; ">无</span></td>';
                            }
                    currentCustomerId = rowData['customer_id'];
                } else {
                    row += '<tr>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>' + 
                            '<td></td>';
                }
                
                //预期回访时间
                var showDate = (rowData['rv_date'] ? rowData['rv_date'] : '');
                var deptName = (rowData['dept_name'] ? rowData['dept_name'] : '');
                //将预回访日期转为时间戳(毫秒)
                str = showDate.replace(/-/g,'/'); 
                var strTime = new Date(str);
                var showtime = strTime.getTime();
                //当前时间戳(毫秒)
                var myDate = new Date();
                var time = myDate.valueOf(); 
                //如果预期回访时间和现在时间差超过7天,将标红提醒
                if ((time-showtime) > 6*24*3600*1000 && rvTime == '' ) {
                    row +=  '<td>' + rowData['rvt_type'] + '</td>' + 
                            '<td>' + rvPlan + '</td>' + 
                            '<td>' + rowData['admin_name'] + '</td>' + 
                            '<td>' + deptName + '</td>' + 
                            '<td class="text-center"><span style="color: red; ">' + showDate + '</span></td>' + 
                            '<td class="text-center"><span style="color: red; ">超过7天未回访</span></td>' + 
                            // '<td class="text-center">' + rvStatus + '</td>' + 
                            '<td style="width: 180px;"><span style="max-height: 92px; overflow: overlay;display: inline-block;">' + rowData['rvi_content'] + '</span></td>' + 
                            '<td>' + rvCreatetime + '</td>' + 
                            '<td>' + fatName + '</td>' + 
                        '</tr>';
                } else {
                    row +=  '<td>' + rowData['rvt_type'] + '</td>' + 
                            '<td>' + rvPlan + '</td>' + 
                            '<td>' + rowData['admin_name'] + '</td>' + 
                            '<td>' + deptName + '</td>' + 
                            '<td class="text-center">' + showDate + '</td>' + 
                            '<td class="text-center">' + rvTime + '</td>' + 
                            // '<td class="text-center">' + rvStatus + '</td>' + 
                            '<td style="width: 180px;"><span style="max-height: 92px; overflow: overlay;display: inline-block;">' + rowData['rvi_content'] + '</span></td>' + 
                            '<td>' + rvCreatetime + '</td>' + 
                            '<td>' + fatName + '</td>' + 
                        '</tr>';
                }

                $('#table tbody').append(row);
            }
        }
    };
    var currentCustomerId = -1;
    var genders = {0: '隐私', 1: '女', 2: '男'};
    var currentNo = 0;
    return Controller;
});