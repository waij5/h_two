define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-datetimepicker','bootstrap-select'], function ($, undefined, Backend, Table, Form,undefined,bootstrapSelect) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({
                extend: {
                    index_url: 'wm/cm/index',
                    add_url: 'wm/cm/add',
                    edit_url: 'wm/cm/edit',
                    multi_url: 'wm/cm/multi',
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
                        {checkbox: true},
                        {field: 'pro_id', title: __('Id')},
                        {field: 'pro_code', title: __('Pro_code')},
                        {field: 'pro_name', title: __('Pro_name'),class:'red'},
                        {field: 'pro_spell', title: __('Pro_spell')},
                        {field: 'pro_cost', title: __('Pro_cost')},
                        {field: 'pro_amount', title: __('Pro_amount')},
                        {field: 'depot_name', title: __('Depot_id')},
                        {field: 'u_name', title: __('Pro_unit')},
                        {field: 'pro_spec', title: __('Pro_spec')},
                        {field: 'pdc_name', title: __('Subject_name')},
                        {field: 'pro_cat2', title: __('Pro_cat')},
                        {field: 'pro_stock', title: __('Pro_stock')},
                        {field: 'pro_status', title: __('Pro_status'), formatter: Backend.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},

                        {field: 'operate', title: __('Operate'), 
                        // events: Controller.api.events.operate,
                        events: {
                                
                                'click .btn-editone': Table.api.events.operate['click .btn-editone'],

                               
                                'click .btn-search': function (e, value, row, index) {
                                    Fast.api.open('/wm/cm/searchlot/ids/' + row['pro_id'],'耗材下属批号信息  id:'+row['pro_id']);
                                },
                                'click .btn-del': function (e, value, row, index) {
                                    // Fast.api.ajax('/wm/drugs/delDrugs/ids/' + row['pro_id'],'删除');
                                    if(confirm('是否确认删除？')){
                                        $.ajax({
                                            type: 'POST',
                                            url: '/wm/cm/delCm',
                                            data: {pro_id:row['pro_id']},
                                            success: function(msg){
                                                if(msg == '1'){
                                                    Toastr.error('已有进库记录，不可删除！');
                                                }else if(msg == '2'){
                                                    Toastr.success('删除成功！');
                                                    setTimeout(function () {
                                                        // location.reload();
                                                        $('.fa-refresh').click();
                                                    }, 2000);
                                                }else if(msg == '3'){
                                                    Toastr.error('删除失败！');
                                                }
                                                
                                                
                                            }
                                        })
                                    }
                                },
                            },
                        formatter: function(value, row) {
                            var operateHtml = '<a href="javascript:;" class="btn btn-xs btn-success btn-editone" title="编辑"><i class="fa fa-pencil"></i></a> ';
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-success btn-search" title="查看批号"><i class="fa fa-search"></i></a> ';
                                operateHtml += '<a href="javascript:;" class="btn btn-xs btn-danger btn-del" title="删除"><i class="fa fa-trash"></i></a>';
                            return operateHtml;
                        }},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],

                onLoadSuccess: function(data) {
                    $(table).find('[data-toggle="tooltip"]').tooltip();
                },
                onRefresh: function(params) {
                    if (params && params.query) {
                        currentOp = params.query.op;
                        currentFilter = params.query.filter;
                    }
                },
            });

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    pro_id: '=',
                    pro_code: '=',
                    pro_name: 'LIKE %...%',
                    pro_spell: 'LIKE %...%',
                    depot_id: '=',
                    pro_cat1: '=',
                    pro_cat2: '=',
                    stime: '',
                    etime: '',
                    
                });
            });
            Form.events.datetimepicker($("form[role=form]"));

            $('#c-pro_cat1').change(function(){
                    var subject_id = $(this).val();
                    // alert(subject_id);
                    var url = '/wm/cm/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pro_cat2').html(msg);
                            
                        }
                    })
                    // alert(subject_id);
                })

            //导出
            $('#btn-export').on('click', function() {
                var url = '/wm/cm/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter) ;
                Fast.api.open(url, __('Downloading page'));
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('/wm/cm/downloadprocess');
        },
        add: function () {
            Controller.api.bindevent();
           
            $('.getNum').click(function(){
                var num = 1;
                var url = '/wm/cm/getNum';
                // alert(num);
                $.ajax({
                        type: 'POST',
                        url: url,
                        data: {num:num},
                        dataType: 'json',
                        success: function(msg){
                            // alert(msg);
                            // var mss = eval(msg);
                            $('#c-pro_code').attr('value', msg);
                            // 
                        }
                    })
            })

            $('#c-pro_cat1').change(function(){
                    var subject_id = $(this).val();
                    // alert(subject_id);
                    var url = '/wm/cm/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pro_cat2').html(msg);
                            
                        }
                    })
                    // alert(subject_id);
                })
            
            
        },
        edit: function () {
            Controller.api.bindevent();
            
            $("#c-price").change(function(){
                var price = $(this).val();
                $('#c-thisprice').attr('value', price);
            })

            $('#c-pro_cat1').change(function(){
                    var subject_id = $(this).val();
                    // alert(subject_id);
                    var url = '/wm/cm/ajaxSubject';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {subject_id:subject_id},
                        success: function(msg){
                            // var mss = eval(msg);
                            $('#c-pro_cat2').html(msg);
                            
                        }
                    })
                    // alert(subject_id);
                })
                // <!-- 2017-09-28  子非魚 -->
        },
        searchlot: function () {
            Controller.api.bindevent();
            
            $('#table').on('click', '.change_cost', function() {
                var lot_id = $(this).data('lot');
                var htmlcost = '<input class="lcosts" type="text" value="0" name="lcost"  size="5">';
                var htmlprice = '<input class="lprices" type="text" value="0" name="lprice"  size="5">';
                var htmloperate = '<button class="btn btn-xs btn-success confirmss" data-lots='+lot_id+' >确&nbsp;&nbsp;&nbsp;定</button >';
                $(this).parents('tr').find('.lcost').html(htmlcost);
                $(this).parents('tr').find('.lprice').html(htmlprice);
                $(this).parents('tr').find('.operate').html(htmloperate);
                // $(this).
                
                // alert(lot_id);

            });

            $('#table').on('click', '.confirmss', function() {
                var lot_ids = $(this).data('lots');
                var url = '/wm/cm/changecost';
                var lcosts = $(this).parents('tr').find('.lcosts').val();
                var lprices = $(this).parents('tr').find('.lprices').val();
                var reg = /^[0-9]+(\.[0-9]{1,4})?$/;
                if(reg.test(lcosts)==true && reg.test(lprices)==true){

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {lot_ids:lot_ids, lcosts:lcosts, lprices:lprices},
                        success: function(msg){
                            if(msg == '1'){
                                Toastr.success('改价成功！');
                                window.location.reload();
                            }else if(msg == '2'){
                                Toastr.error('无效传值，改价失败！')
                            }else if(msg == '3'){
                                Toastr.error('改价失败！');
                            }
                            
                        }
                    });


                }else if(reg.test(lcosts)==false){
                    Toastr.error('请输入正确的成本单价！');
                }else if(reg.test(lprices)==false){
                    Toastr.error('请输入正确的零售价！');
                }
                // alert(reg.test(lcosts));
                // alert(lot_ids);alert(lcosts);alert(lprices);
            });


            //修改有效日期
            $('#table').on('click', '.change_letime', function() {
                var lot_id = $(this).data('lots');
                // var htmlLetime = '<input type="text" value="" class="datetimepicker" class="letime" data-date-format="YYYY-MM-DD" name="letime"  size="7">';
                $(this).parents('tr').find('.letime').addClass('disnone');
                $(this).parents('tr').find('.letimess').removeClass('disnone');
                var htmloperate = '<button class="btn btn-xs btn-primary sureChangeLetime" data-lotss='+lot_id+' >确&nbsp;&nbsp;&nbsp;定</button >';
                // $(this).parents('tr').find('.letime').append(htmlLetime);
                // var ahtml =$(htmlLetime);
                $(this).parents('tr').find('.operate').html(htmloperate);
                
                // document.getElementsByName('.datetimepicker');
                // document.getElementById("letime").type="text";
                
                // alert(lot_id);

            });

            $('.datetimepicker').datetimepicker(dateGoods);

            $('#table').on('click', '.sureChangeLetime', function() {
                var lot_id = $(this).data('lotss');
                // console.log(lot_id);
                var url = '/wm/cm/changeletime';
                var letime = $(this).parents('tr').find('.letimesss').val();
                // console.log(letime);
                var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
                if(reg.test(letime)==true ){

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {lot_id:lot_id, letime:letime},
                        success: function(msg){
                            if(msg == '1'){
                                Toastr.success('更改效期成功！');
                                window.location.reload();
                            }else if(msg == '2'){
                                Toastr.error('无效传值，改价失败！')
                            }else if(msg == '3'){
                                Toastr.error('更改效期失败！');
                            }
                            window.location.reload();
                            // console.log(msg);
                        }
                    });
                    // console.log(111111111);


                }else{
                    Toastr.error('请输入正确的有效日期！');
                }
            });


        },


        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    var dateGoods =    //日期插件
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