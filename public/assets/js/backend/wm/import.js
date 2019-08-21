define(['jquery', 'bootstrap', 'backend', 'table', 'jquery-freezeheader','form','bootstrap-datetimepicker'], function ($, undefined, Backend, Table,undefined, Form, undefined) {
//日期插件 'bootstrap-datetimepicker'     undefined
    var Controller = {
    	index: function () {
           	
    

            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            // Table.api.bindevent(table);
            /*$('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });*/
        



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