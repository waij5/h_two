define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        operatebenefit: function() {
            var indexUrl = 'stat/benefit/operatebenefit';
            Table.api.init({});
            var table = $('#table');
            currentFilter = '';
            currentOp = '';
            table.bootstrapTable({
                url: indexUrl,
                commonSearch: false,
                search: false,
                escape: false,
                sortName: 'rec.createtime asc, staff_rec.admin_id',
                sortOrder: 'asc',
                pk: 'staff_rec.id',
                columns: [
                    [{
                        field: 'nickname',
                        title: __('Staff'),
                    },{
                        field: 'deduct_role_name',
                        title: '角色',
                    },
                     {
                        field: 'createtime',
                        title: __('deduct time'),
                        formatter: Backend.api.formatter.date
                    },
                    {
                        field: 'pro_name',
                        title: __('pro_name'),
                        // formatter: function(value, row, index) {
                        //     // return Backend.api.formatter.content(value, row, index, 10);
                        // },
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                        width: '120px',
                                        'word-wrap': 'break-word',
                                }
                            }
                        },
                    }, {
                        field: 'pro_spec',
                        title: __('pro_spec'),
                        // formatter: function(value, row, index) {
                        //     // return Backend.api.formatter.content(value, row, index, 10);
                        // },
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                        width: '120px',
                                        'word-wrap': 'break-word',
                                }
                            }
                        },
                    }, {
                        field: 'item_type',
                        title: __('item_type'),
                        formatter: function(value) {
                            return __('Pro_type_' + value);
                        }
                    }, {
                        field: 'customer_id',
                        title: __('customer'),
                        formatter: function(value, row, index) {
                            return '<' + value + '>' + row.ctm_name;
                        },
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    // "text-align" : "left !important"
                                }
                            };
                        },
                    }, {
                        field: 'deduct_times',
                        title: __('deduct_times')
                    }, {
                        field: 'deduct_amount',
                        title: __('deduct_amount')
                    }, {
                        field: 'final_percent',
                        title: __('final_percent')
                    }, {
                        field: 'final_amount',
                        title: __('final_amount')
                    }, {
                        field: 'final_benefit_amount',
                        title: __('final_benefit_amount')
                    }, ]
                ],
                onLoadSuccess: function(data) {
                    //提示工具
                    // $("[data-toggle='tooltip']").tooltip();
                    if (data.summary) {
                        for (var i in data.summary) {
                            if ($('#h_' + i).length) {
                                $('#h_' + i).text(data.summary[i]);
                            }
                        }
                    }
                },
                onRefresh: function(params) {
                    currentFilter = params.query.filter;
                    currentOp = params.query.op;
                },
            });
            Table.api.bindevent(table);
            // 搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            Form.events.datetimepicker($('.form-commonsearch'));
            Form.events.selectpicker($('.form-commonsearch'));
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'rec.createtime': 'BETWEEN',
                    'staff_rec.admin_id': '=',
                    'admin.dept_id': '=',
                    'items.pro_name': 'LIKE %...%',
                });
            });
            // $('.selectpicker').selectpicker({
            //     'selectedText': '请选择'
            // });
            // $('button[type="reset"]').click(function() {
            //     $('.bootstrap-select').each(function(index) {
            //         var searchId = $(this).find('.selectpicker').attr('name');
            //         var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
            //         $(this).find('.dropdown-toggle').attr('title', defaultVal).attr('data-id', searchId).removeClass('bs-placeholder');
            //         $(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
            //         $(this).find('.inner').find('li').eq(0).addClass('selected active');
            //         $(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
            //         $(this).find('.inner').find('li').removeClass('hidden');
            //     })
            // })
            $type = 'operatebenefit';
            $('#btn-export').on('click', function() {
                var url = '/stat/benefit/downloadprocess' + '?type=' + $type + '&op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter);
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            var type = Fast.api.query('type', window.location.href);
            return Backend.api.commondownloadprocess('/stat/benefit/downloadprocess?type=' + type);
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
        }
    };
    return Controller;
});