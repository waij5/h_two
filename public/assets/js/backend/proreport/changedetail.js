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
            //打印功能
            // $('#isPrint').click(function(){
            //     // $('.printRemove').remove();    //指定打印内容中多余部分移除
            //     if(confirm('是否开始打印?')){
            //         bdhtml=window.document.body.innerHTML;
            //         sprnstr="<!--startprint-->"; //开始打印标识字符串有17个字符
            //         eprnstr="<!--endprint-->"; //结束打印标识字符串
            //         prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); //从开始打印标识之后的内容
            //         prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); //截取开始标识和结束标识之间的内容
            //         window.document.body.innerHTML=prnhtml; //把需要打印的指定内容赋给body.innerHTML
            //         window.print(); //调用浏览器的打印功能打印指定区域
            //     }else{
            //         return false;
            //     }
            // })

            Form.events.datetimepicker($("form[role=form]"));
            // 为表格绑定事件
            // Table.api.bindevent(table);
            $('#btn-customer-clear').on('click', function() {
                $('.clear').val('');
            });

         

            $('#btn-export').on('click', function() {
                var yjyWhere = $('#h_yjy_where').text();
                var url = '/proreport/changedetail/downloadprocess?yjyWhere=' + encodeURI(yjyWhere);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            var yjyWhere = Fast.api.query('where', window.location.href);
            return Backend.api.commondownloadprocess('/proreport/changedetail/downloadprocess');
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