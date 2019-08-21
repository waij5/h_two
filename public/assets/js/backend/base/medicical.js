define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/medicical/index',
                    add_url: 'base/medicical/add',
                    edit_url: 'base/medicical/edit',
                    del_url: 'base/medicical/del',
                    multi_url: 'base/medicical/multi',
                    table: 'medicical',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'med_id',
                search: false,
                sortName: 'med_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'med_id', title: __('Med_id')},
                        {field: 'med_code', title: __('Med_code')},
                        {field: 'med_name', title: __('Med_name')},
                        {field: 'med_spell', title: __('Med_spell')},
                        {field: 'med_print', title: __('Med_print')},
                        {field: 'med_type', title: __('Med_type')},
                        {field: 'med_form', title: __('Med_form')},
                        {field: 'med_pkg_spec', title: __('Med_pkg_spec')},
                        {field: 'med_pkg_unit', title: __('Med_pkg_unit')},
                        {field: 'med_min_unit', title: __('Med_min_unit')},
                        {field: 'med_unit_radix', title: __('Med_unit_radix')},
                        {field: 'med_origin', title: __('Med_origin')},
                        {field: 'med_maker', title: __('Med_maker')},
                        {field: 'storage_id', title: __('Storage_id')},
                        {field: 'med_cat1', title: __('Med_cat1')},
                        {field: 'med_cat2', title: __('Med_cat2')},
                        {field: 'med_license', title: __('Med_license')},
                        {field: 'med_registration', title: __('Med_registration')},
                        {field: 'med_is_import', title: __('Med_is_import')},
                        {field: 'med_unit', title: __('Med_unit')},
                        {field: 'med_spec', title: __('Med_spec')},
                        {field: 'med_max_stock', title: __('Med_max_stock')},
                        {field: 'med_min_stock', title: __('Med_min_stock')},
                        {field: 'med_use_times', title: __('Med_use_times')},
                        {field: 'med_price', title: __('Med_price')},
                        {field: 'local_price', title: __('Local_price')},
                        {field: 'med_min_price', title: __('Med_min_price')},
                        {field: 'med_cost', title: __('Med_cost')},
                        {field: 'subject_type', title: __('Subject_type')},
                        {field: 'deduct_addr', title: __('Deduct_addr')},
                        {field: 'dept_id', title: __('Dept_id')},
                        {field: 'med_fee_type', title: __('Med_fee_type')},
                        {field: 'med_deadline', title: __('Med_deadline')},
                        {field: 'allow_position_bonus', title: __('Allow_position_bonus')},
                        {field: 'allow_bonus', title: __('Allow_bonus')},
                        {field: 'med_status', title: __('Med_status'), formatter: Table.api.formatter.status},
                        {field: 'med_sort', title: __('Med_sort')},
                        {field: 'med_remark', title: __('Med_remark')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
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