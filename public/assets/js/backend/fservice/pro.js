define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fservice/pro/index',
                    add_url: 'fservice/pro/add',
                    edit_url: 'fservice/pro/edit',
                    del_url: 'fservice/pro/del',
                    multi_url: 'fservice/pro/multi',
                    table: 'fpro',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'id',
                        title: __('Id')
                    }, {
                        field: 'pro_id',
                        title: __('Pro_id')
                    }, {
                        field: 'cover',
                        title: __('Cover')
                    }, {
                        field: 'video',
                        title: __('Video')
                    }, {
                        field: 'short_desc',
                        title: __('Short_desc')
                    }, {
                        field: 'status',
                        title: __('Status'),
                        formatter: Table.api.formatter.status
                    }, {
                        field: 'operate',
                        title: __('Operate'),
                        table: table,
                        events: Table.api.events.operate,
                        formatter: Table.api.formatter.operate
                    }]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        comselectpop: function() {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            Controller.api.bindevent();
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'pro.pro_id': '=',
                    pro_name: 'LIKE %...%',
                    pro_code: 'LIKE %...%',
                    pro_spec: 'LIKE %...%',
                    'pro.dept_id': '=',
                    pro_fee_type: '=',
                    pro_cat1: '=',
                    pro_cat2: '=',
                });
            });
            $(document).on("change", "#pro_cat1", function() {
                var cate = $('#pro_cat1').val();
                var tArg = arguments;
                $.ajax({
                    url: "base/project/getLv2Cate",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        cate_id: cate
                    },
                    success: function(data) {
                        $('#pro_cat2').html('');
                        for (var i in data) {
                            $('#pro_cat2').append('<option value="' + i + '"' + (i ? '' : ' selected') + '>' + data[i] + '</option>');
                        }
                        if (tArg.length >= 2) {
                            var initValue = tArg[0];
                            $('#pro_cat2').val(initValue);
                            // changeCate1(tArg[1]);
                        } else {
                            // changeCate1();
                        }
                    }
                });
            })
        },
        add: function() {
            Backend.api.initYjySwitcher();
            Controller.api.bindevent();
        },
        edit: function() {
            Backend.api.initYjySwitcher();
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});