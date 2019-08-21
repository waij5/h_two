define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/msg/index',
                    // add_url: 'base/msg/add',
                    edit_url: 'base/msg/edit',
                    del_url: 'base/msg/del',
                    multi_url: 'base/msg/multi',
                    table: 'msg',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'msg_id',
                sortName: 'msg_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'msg_id', title: __('Msg_id')},
                        {field: 'msg_type', title: __('Msg_type'), formatter: yjyApi.formatter.msgtype},
                        // {field: 'msg_from', title: __('Msg_from')},
                        {field: 'msg_from_admin_name', title: __('Msg_from')},
                        // {field: 'msg_to', title: __('Msg_to')},
                        {field: 'msg_to_admin_name', title: __('Msg_to')},
                        {field: 'msg_title', title: __('Msg_title')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            //搜索
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    msg_id: '=',
                    msg_type: '=',
                    msg_title: 'LIKE %...%',
                    createtime: 'BETWEEN',
                    // updatetime: 'BETWEEN',
                });
            });
        },
        add: function () {
            
            Controller.api.bindevent();
        },
        edit: function () {
            //回访记录
            document.getElementById("addRvinfoHistory").onclick=function(e){                
                var ctm_id = $(this).attr('value');
                Fast.api.open("customer/rvinfo/add?ctm_id="+ctm_id, __('Add'));
            };

            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    var yjyApi = {
       formatter: {
            msgtype: function(value) {
                return __('msgtype_' + value);
            }
       }
    };

    return Controller;
});