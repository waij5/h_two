define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/protype/index',
                    add_url: 'store/protype/add',
                    edit_url: 'store/protype/edit',
                    del_url: 'store/protype/del',
                    multi_url: 'store/protype/multi',
                    table: 'protype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                // sortName: 'weigh',
                search: false,
                commonSearch: false,pagination: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'type_text', title: __('Type'), operate:false},
                        {field: 'pid', title: '上级分类'},
                        {field: 'code', title: __('Code')},
                        {field: 'name', title: __('Name'), align: 'left'},
                        // {field: 'weigh', title: __('Weigh')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark')},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            //搜索
            // $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            //     $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
            //         $('.commonsearch-table').toggleClass('hidden');
            //     });
            // $("form.form-commonsearch").off('submit').on("submit", function(event) {
            //     event.preventDefault();
            //     return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
            //         type: '=',
            //         pid: '=',
            //         code: 'LIKE %...%',
            //         name: '=',
            //         // createtime: 'BETWEEN',
            //         // updatetime: 'BETWEEN',
            //     });
            // });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            $('#sure').on('click', function() {
                $("#c-type").removeAttr("disabled");
            });
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