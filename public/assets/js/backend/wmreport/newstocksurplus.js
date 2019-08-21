define(['jquery', 'bootstrap', 'backend', 'table', 'jquery-freezeheader','form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table,undefined, Form, undefined) {
//日期插件 'bootstrap-datetimepicker'     undefined
    var Controller = {
    	index: function () {

             // 初始化表格参数配置
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({
                extend: {
                    index_url: 'wmreport/newstocksurplus/index',
                    table: 'project',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'pro_id',
                sortName: 'pro_id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'pro_code', title: '产品编号'},
                        {field: 'pro_name', title: '产品名称',
                            cellStyle: function() {
                                return {
                                    css: {
                                        'color': 'red',
                                    },
                                }
                            },},
                    ]
                ]
            });

           /* {field: 'pro_name', title: '产品名称',
                            cellStyle: function() {
                                return {
                                    css: {
                                        'color': 'red',
                                    },
                                }
                            },},*/

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'p.pro_code': '=',
                    'p.pro_name': 'LIKE %...%',
                    'p.depot_id': '=',
                    stime:'',
                    etime:'',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            Form.events.datetimepicker($("form[role=form]"));
            

            $('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });

        $('#btn-export').on('click', function() {
            var yjyWhere = $('#h_yjy_where').text();
            var url = '/wmreport/newstocksurplus/downloadprocess?yjyWhere=' + encodeURI(yjyWhere);
            Fast.api.open(url, __('Downloading page'));
        });

        $('.fixed-table-toolbar').hide();

        // 为表格绑定事件
            Table.api.bindevent(table);


        },


        downloadprocess: function() {
            var yjyWhere = Fast.api.query('where', window.location.href);
            return Backend.api.commondownloadprocess('/wmreport/newstocksurplus/downloadprocess');
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