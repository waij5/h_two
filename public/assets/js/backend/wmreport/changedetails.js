define(['jquery', 'bootstrap', 'backend', 'table', 'jquery-freezeheader', 'form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table, undefined,Form, undefined) {
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
            }
            

            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            // Table.api.bindevent(table);
            $('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });

         

            $('#btn-export').on('click', function() {
                var yjyWhere = $('#h_yjy_where').text();
                var url = '/wmreport/changedetails/downloadprocess?yjyWhere=' + encodeURI(yjyWhere);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            var yjyWhere = Fast.api.query('where', window.location.href);
            return Backend.api.commondownloadprocess('/wmreport/changedetails/downloadprocess');
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