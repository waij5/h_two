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
            var currentOp = '';
            var currentFilter = '';

            Table.api.init({});
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: '/stat/rvinfosummary/index',
                commonSearch: false,
                search: false,
//              searchOnEnterKey: false,
                escape: false,
                pagination: false,
                columns: [
                    [   
                        {field: 'admin_id', title: 'No.', formatter: function(value, row, index) {
                            return index + 1;
                        }},
                        { field: 'nickname', title: __('Revisit staff'), formatter: function(value, row, index) {
                            if (row.username) {
                                return '<' + row.username + '>' + value;
                            } else {
                                return '--' + row.admin_id + '--';
                            }
                        } },
                        { field: 'count', title: __('Rvinfo count')},
                        { field: 'avaiable_count', title: __('Avaiable visited count')},
                        { field: 'effective', title: __('Effective rvinfo'), formatter: function(value, row, index) {
                            return (Math.floor((row.avaiable_count/row.count)*10000)/100);
                        }},
                        { field: 'customer_count', title: __('Rvinfo customer count')},
                        { field: 'avaiable_customer_count', title: __('Avaiable visited customer count')},
                        { field: 'effective', title: __('Effective customer'), formatter: function(value, row, index) {
                            return (Math.floor((row.avaiable_customer_count/row.customer_count)*10000)/100);
                        }},
                    ]
                ],
                onLoadSuccess: function(data) {
                    if (data.summary && typeof data.summary.item_pay_total != 'undefined') {
                        var unused_total = (data.summary.item_pay_total - data.summary.item_used_pay_total).toFixed(2);
                        $('#total').html(data.summary.item_pay_total ? data.summary.item_pay_total : 0);
                        $('#count').html(data.summary.count ? data.summary.count : 0);
                        $('#item_used_pay_total').html(data.summary.item_used_pay_total ? data.summary.item_used_pay_total : 0);
                        $('#unused_total').html(unused_total ? unused_total : 0);
                        $('#total_times').html(data.summary.total_times ? data.summary.total_times : 0);
                        $('#used_total_times').html(data.summary.used_total_times ? data.summary.used_total_times : 0);                 
                    }
                },
                onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },
                
            });
            // Form.events.datetimepicker($('.form-commonsearch'));
            Table.api.bindevent(table);
            Controller.api.bindevent();
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function (event) {
                 event.preventDefault();
                 return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                     'notOnlyToday': '=',
                     'rvt_type': '=',
                     'admin.dept_id': '=',
                     'admin_id': '=',
                     'customer.arrive_status': '=',
                     'rv_date': 'BETWEEN',
                     'rv_time': 'BETWEEN',
                     'customer.ctm_first_dept_id': '=',
                     'customer.ctm_first_cpdt_id': '=',
                     'customer.ctm_first_tool_id': '=',
                 });
            });
            // $('#btn-export').on('click', function() {
            //     var url = '/stat/customerorderitems/downloadprocess' + '?type=' + $type + '&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
            //     Fast.api.open(url, __('Downloading page'));
            // });
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
        },
    };

    return Controller;
});