define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'form', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Datatable, Table, Form, Echarts) {

    var Controller = {
        index: function () {


            $(document).ready(function() {
                fixTableHNdF();
                $(window).resize(function() {
                    var calcHeight = $(window).height() - $('#consumTable').offset().top - 60;
                    var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200) + 'px';
                    $('#consumTable').css('max-height', tbodyHeight);
                    var table = $('#consumTable .fixTable');
                    ajustWidth(table);
                });
            })
                        
            function ajustWidth(table) {
                //              var table = $('#consumTable .fixTable')
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
                                //                              Controller.api.ajustWidth(table);
                                break;
                            }
                        }
                        (scrollTop === 0) ? fixTable.addClass('hidden'): fixTable.removeClass('hidden');
                        fixTable.css(style);
                    } else {
                        var html = $('#consumTable .scrolltable thead ').get(0).innerHTML;
                        var table = $('<table class="table table-striped table-bordered table-hover scrolltable fixTable stockbalance_thead"><thead>' + html + '</thead></table>');
                        table.css(style);
                        ajustWidth(table);
                        $(id).append($(table));
                        fixTable = $(this).find('.fixTable');
                    }
                });    
            }



            
            // 基于准备好的dom，初始化echarts实例
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '',
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['下单', '成交']
                },
                toolbox: {
                    show: false,
                    feature: {
                        magicType: {show: true, type: ['stack', 'tiled']},
                        saveAsImage: {show: true}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: Orderdata.column
                },
                yAxis: {

                },
                grid: [{
                        left: 'left',
                        top: 'top',
                        right: '10',
                        bottom: 30
                    }],
                series: [{
                        name: '成交',
                        type: 'line',
                        smooth: true,
                        areaStyle: {
                            normal: {
                            }
                        },
                        lineStyle: {
                            normal: {
                                width: 1.5
                            }
                        },
                        data: Orderdata.paydata
                    },
                    {
                        name: '下单',
                        type: 'line',
                        smooth: true,
                        areaStyle: {
                            normal: {
                            }
                        },
                        lineStyle: {
                            normal: {
                                width: 1.5
                            }
                        },
                        data: Orderdata.createdata
                    }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);

            //动态添加数据，可以通过Ajax获取数据然后填充
            setInterval(function () {
                Orderdata.column.push((new Date()).toLocaleTimeString().replace(/^\D*/, ''));
                var amount = Math.floor(Math.random() * 200) + 20;
                Orderdata.createdata.push(amount);
                Orderdata.paydata.push(Math.floor(Math.random() * amount) + 1);

                //按自己需求可以取消这个限制
                if (Orderdata.column.length >= 20) {
                    //移除最开始的一条数据
                    Orderdata.column.shift();
                    Orderdata.paydata.shift();
                    Orderdata.createdata.shift();
                }
                myChart.setOption({
                    xAxis: {
                        data: Orderdata.column
                    },
                    series: [{
                            name: '成交',
                            data: Orderdata.paydata
                        },
                        {
                            name: '下单',
                            data: Orderdata.createdata
                        }]
                });
            }, 2000);

            $(window).resize(function () {
                myChart.resize();
            });


            Table.api.init({
                extend: {
                    index_url: 'base/msg/index',
                    // add_url: 'base/msg/add',
                    edit_url: 'base/msg/edit',
                    del_url: 'base/msg/del',
                    multi_url: 'base/msg/multi',
                    table: 'msg',
                }
            });

            var table = $("#msg-table");

            // 初始化表格
            table.bootstrapTable({
                url: 'base/msg',
                pk: 'msg_id',
                sortName: 'msg_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'msg_id', title: __('Msg_id')},
                        {field: 'msg_type', title: __('Msg_type'),
                            formatter: function (value) {
                                return __('msgtype_' + value);
                            }
                        },
                        // {field: 'msg_from', title: __('Msg_from')},
                        {field: 'msg_from_admin_name', title: __('Msg_from')},
                        // {field: 'msg_to', title: __('Msg_to')},
                        // {field: 'msg_to_admin_name', title: __('Msg_to')},
                        {field: 'msg_title', title: __('Msg_title')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            Table.api.bindevent(table);

            //birth alert table
            var customerTable = $("#customer-table");
            customerTable.bootstrapTable({
                url: 'customer/customer/listofbirth',
                commonSearch: false,
                search: false,
                pk: 'ctm_id',
                sortName: 'ctm_id',
                columns: [
                    [{
                            checkbox: true
                        }, {
                            field: 'arrive_status',
                            title: __('Arrive_status'),
                            formatter: function(value) {
                                var text = '';
                                var cssCls = '';
                                if (value == 0) {
                                    text = '<i class="fa fa-circle text-danger"></i>' + __('arrive_no');
                                } else {
                                    text = '<i class="fa fa-circle text-success"></i>' + __('arrive_yes');
                                }
                                return text;
                            }
                        }, {
                            field: 'ctm_id',
                            title: __('Ctm_id')
                        },
                        {
                            field: 'ctm_name',
                            title: __('Ctm_name'),
                            formatter: function(value, row, index) {
                                var str = '<a class = "btn-clickviewsoneInfo" title="点击查看顾客信息">' + row.ctm_name + '</a>';
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        'cursor': 'pointer',
                                        'white-space': 'nowrap',
                                    }
                                }
                            },
                            events: {
                                'click .btn-clickviewsoneInfo': function(e, value, row, index) {
                                    $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                                    $('.detailIframe').attr('src', '/cash/order/deductview/ids/' + row.ctm_id);
                                }
                            },
                        }, {
                            field: 'ctm_sex',
                            title: __('Ctm_sex')
                        },
                        {
                            field: 'ctm_addr',
                            title: __('Ctm_addr'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 16)
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        },
                        {
                            field: 'ctm_mobile',
                            title: __('Ctm_mobile')
                        },
                        {
                            field: 'ctm_explore',
                            title: __('Ctm_explore'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'ctm_source',
                            title: __('Ctm_source')
                        },
                        {
                            field: 'ctm_job',
                            title: __('Ctm_job'),
                        }, {
                            field: 'developStaffName',
                            title: __('developStaff'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'ctm_remark',
                            title: __('Ctm_remark'),
                            formatter: function(value, row, index) {
                                return Backend.api.formatter.content(value, row, index, 14)
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    $(this).parents('tr').addClass('deepShow').siblings('tr').removeClass('deepShow');
                                    $('.detailIframe').attr('src', '/customer/customer/edit/ids/' + row.ctm_id);
                                },
                                'click .btn-delone': Table.api.events.operate['click .btn-delone'],
                            },
                            formatter: function(value, row, index) {
                                var operateHtml = ' <a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a>';
                                return operateHtml;
                            }
                        }
                    ]
                ],
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    if ($('.tdDetail').length == 0) {
                        var tdDetail = "<div class='tdDetail'><iframe class='detailIframe'></iframe></div>";
                        customerTable.parents('.bootstrap-table').append(tdDetail);
                        customerTable.parents('.bootstrap-table').find('.detailIframe').contents().find('body').append('<center style="position: absolute; left: 50%; top: 25%; width: 100%; transform: translateX(-50%);"><p style="font-size: 24px;">请点击 <strong style="color: #18bc9c">顾客姓名</strong> 或者 <strong style="color: #18bc9c">相关操作按钮</strong> 显示</p></center>');
                        var contentTableHeight = $(window).height() - 25;
                        customerTable.parents('.bootstrap-table').find('.contentTable').css('height', contentTableHeight);
                        var iframeHeight = $(window).height() - 75;
                        var tableHeight = $(window).height() - 155;
                        customerTable.parents('.bootstrap-table').find('.fixed-table-body').css('height', tableHeight);
                        $('#rightbar').css('height', iframeHeight);
                        customerTable.parents('.bootstrap-table').find('.fixed-table-container').css('width', '48%').css('float', 'left');
                        $('.tdDetail').css('width', '52%').css('float', 'left');
                        $('.tdDetail').css('height', iframeHeight);
                    } else {
                    }
                },
            });
            Table.api.bindevent(customerTable);


            Form.api.bindevent($("form[role=form]"));

            //回访计划  带个参数到客户回访，只打开回访计划，记录不显示
            $('#rvtype').on('click',function(){
                // Fast.api.open("customer/rvinfo/index?ctm_id="+ctm_id, __('Add'));
                window.location.href = 'customer/rvinfo/index';

            })

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    msg_id: '=',
                    msg_type: '=',
                    msg_title: 'LIKE %...%',
                    createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });

            if ($('#i-just-login').val()) {
                $.ajax({
                    url: 'customer/rvinfo/todayRevisitNotices',
                    type: 'post',
                    data: {
                            limit: 5,
                        },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $(".notifications-menu > a span").text(data.total > 0 ? data.total : '');
                        // $(".notifications-menu .footer a").attr("href", 'base/msg/index');
                        $.each(data.rows, function (index, row) {
                            var item = '<li><a href="javascript:;" target="_blank"><i class="' + '' + '"></i> ' + row.createtime + '</a></li>';
                            $(item).appendTo($(".notifications-menu ul.menu"));
                        });

                        layer.open({type: 1, content: $('.notifications-menu')});
                    }
                });
            }
        }
    };

    return Controller;
});