define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wm/supplier/index',
                    add_url: 'wm/supplier/add',
                    edit_url: 'wm/supplier/edit',
                    del_url: 'wm/supplier/del',
                    multi_url: 'wm/supplier/multi',
                    table: 'wm_supplier',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'sup_id',
                sortName: 'sup_id',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'sup_id', title: __('Id')},
                        {field: 'sup_name', title: __('Sup_name')},
                        {field: 'sup_contact', title: __('Sup_contact')},
                        {field: 'sup_tel', title: __('Sup_tel')},
                        {field: 'sup_addr', title: __('Sup_addr')},
                        {field: 'sup_type', title: __('Sup_type'),operate:false,formatter: function (value, row, index) {
                            return __('Sup_type ' + value);
                        }},
                        {field: 'sup_status', title: __('Sup_status'), formatter: Backend.api.formatter.status},
                        {field: 'sup_remark', title: __('Sup_remark')},
                        {field: 'sup_stime', title: __('Sup_stime'), formatter: Table.api.formatter.datetime},
                        {field: 'sup_etime', title: __('Sup_etime'), formatter: Table.api.formatter.datetime},
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
                    sup_type: '=',
                    sup_name: 'LIKE %...%',
                    sup_contact: 'LIKE %...%',
                    sup_tel: 'LIKE %...%',
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