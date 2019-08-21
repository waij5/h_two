define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/producer/index',
                    add_url: 'store/producer/add',
                    edit_url: 'store/producer/edit',
                    del_url: 'store/producer/del',
                    multi_url: 'store/producer/multi',
                    table: 'producer',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'is_pro_text', title: __('Is_pro'), operate:false},
                        {field: 'proname', title: __('Proname')},
                        {field: 'shortname', title: __('Shortname')},
                        {field: 'contact', title: __('Contact')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'addr', title: __('Addr')},
                        {field: 'type', title: __('Type'),operate:false,formatter: function (value, row, index) {
                            return __('Type ' + value);
                        }},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    is_pro: '=',
                    proname: 'LIKE %...%',
                    contact: 'LIKE %...%',
                    tel: 'LIKE %...%',
                    // createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});