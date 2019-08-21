define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/customermaplog/index',
                    add_url: 'customer/customermaplog/add',
                    edit_url: 'customer/customermaplog/edit',
                    del_url: 'customer/customermaplog/del',
                    multi_url: 'customer/customermaplog/multi',
                    synchronization_url: 'customer/customermaplog/synchronization',
                    table: 'Customermaplog',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('id')},
                        {field: 'user_id', title: __('user_id')},
                        {field: 'customer_id', title: __('customer_id')},
                        {field: 'mobile', title: __('mobile')},
                        {field: 'phone', title: __('phone')},
                        {field: 'ctm_name', title: __('ctm_name')},
                        {field: 'user_name', title: __('user_name')},
                        {field: 'real_name', title: __('real_name')},
                        {field: 'nick_name', title: __('nick_name')},
                        // {field: 'rank_points', title: __('rank_points')},
                        // {field: 'pay_points', title: __('pay_points')},
                        // {field: 'deposit_amt', title: __('deposit_amt')},
                        {field: 'createtime', title: __('createtime'),formatter: Table.api.formatter.datetime},
                        // {field: 'sync_time', title: __('sync_time')},
                        
                        {field: 'status', title: __('status'),formatter: yjyApi.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table,formatter: yjyApi.formatter.operate, events: {
                            'click .btn-acceptone': function(e, value, row, index) {
                                var id = row.id;
                                $.ajax({
                                    url: 'customer/customermaplog/synchronization',
                                    data: { ids: id },
                                    dataType: 'json',
                                    success: function(res) {
                                        $("#table").bootstrapTable('refresh');
                                    }
                                })
                            },
                        }}
                    ]
                ]
            });
            
            // $('#toolbar').on("click", ".btn-synchronization", function(e) {
            //     e.preventDefault();
            //     var selectedCustomerIds = [];
            //     var selections = table.bootstrapTable('getSelections');
            //     if (selections.length == 0) {
            //         Layer.msg(__('Nothing selected!'), {
            //             icon: 2
            //         });
            //         return false;
            //     }
            //     for (var curIndex = 0; curIndex < selections.length; curIndex++) {
            //         selectedCustomerIds.push(selections[curIndex]['id']);
            //     }
            //     var selectedCustomerIds = selectedCustomerIds.join(',');
            //     var options = table.bootstrapTable('getOptions');
            //     Fast.api.open(options.extend.synchronization_url + (options.extend.synchronization_url.match(/(\?|&)+/) ? "&ids=" : "?ids=") + selectedCustomerIds, __('batch operate'));
            // });
            // 
            $('.btn-synchronization').on('click', function() {
                var selectedCustomerIds = [];
                var selections = table.bootstrapTable('getSelections');
                if (selections.length == 0) {
                    Layer.msg(__('Nothing selected!'), {
                        icon: 2
                    });
                    return false;
                }
                for (var curIndex = 0; curIndex < selections.length; curIndex++) {
                    selectedCustomerIds.push(selections[curIndex]['id']);
                }
                var selectedCustomerIds = selectedCustomerIds.join(',');
                var options = table.bootstrapTable('getOptions');
                $.ajax({
                    url: 'customer/customermaplog/synchronization',
                    data: { ids: selectedCustomerIds },
                    dataType: 'json',
                    success: function(res) {
                        $("#table").bootstrapTable('refresh');
                    }
                })
            })

            $("#table .btn-acceptone").each(function() {
                $(this).on('click', function() {
               console.log('123');
                    console.log(123);
                })
            })

            

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        synchronization: function () {
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
            status: function(value, row, index) {
                var text = '';
                if(row.status == '1') {
                   text = '<i class="fa fa-circle text-success"></i>';
                }  else {
                   text = '<i class="fa fa-circle text-danger"></i>';
                }
                return text;
            },
            operate: function(value, row, index) {
                var data = '';
                if(row.status == '1') {
                    data = '';
                } else {
                    data = '<a href="javascript:;" class="btn btn-xs btn-success btn-acceptone"><i class="fa fa-check"></i></a>';
                }
                return data;
            }
        }
    }

   

    return Controller;
});