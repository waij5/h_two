define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            var currentOp = '';
            var currentFilter = '';
            Table.api.init({
                extend: {
                    index_url: 'base/project/index',
                    add_url: 'base/project/add',
                    edit_url: 'base/project/edit',
                    del_url: 'base/project/del',
                    multi_url: 'base/project/multi',
                    table: 'project',
                }
            });
            var table = $("#table");

            //判断是否是developer
            var username = $('#username').val();

            var rCols = [
                    [
                        // {
                        //     checkbox: true
                        // },
                        {
                            field: 'fpro_id',
                            title: __('Customize Status'),
                            formatter: function(value, row, index) {
                                // fpro_id fpro_status
                                let iCls = ' text-default';
                                let iVal = '未设置'
                                if (value) {
                                    iCls = ' text-danger';
                                    iVal = '禁用中'
                                    if (row.fpro_status) {
                                        iCls = ' text-success';
                                        iVal = '启用中';
                                    }
                                }
                                return `
                                        <button class="btn btn-default click editFPro"><span class="${iCls}">${iVal}</span><span class="yjy-v-divider"></span><span class="fa fa-pencil text-warning"></span></button>
                                `;
                            },
                            events: {
                                'click .editFPro': function(e, value, row, index) {
                                    e.stopPropagation();
                                    Fast.api.open('/fservice/pro/edit?pro_id=' + row.pro_id, '定制化');
                                    return false;
                                },
                            },
                        }, {
                            field: 'pro_id',
                            title: __('Pro_id')
                        }, {
                            field: 'pro_code',
                            title: __('Pro_code')
                        }, {
                            field: 'pro_name',
                            title: __('Pro_name')
                        }, {
                            field: 'pro_spell',
                            title: __('Pro_spell')
                        }, {
                            field: 'pro_print',
                            title: __('Pro_print')
                        },
                        // {field: 'subject_type', title: __('Subject_type')},
                        {
                            field: 'pro_cat1',
                            title: __('Pro_cat1')
                        }, {
                            field: 'pro_cat2',
                            title: __('Pro_cat2')
                        }, {
                            field: 'pro_cat3',
                            title: __('Pro_cat3')
                        }, {
                            field: 'pro_unit',
                            title: __('Pro_unit')
                        }, {
                            field: 'pro_spec',
                            title: __('Pro_spec')
                        }, {
                            field: 'pro_use_times',
                            title: __('Pro_use_times')
                        }, {
                            field: 'pro_local_amount',
                            title: __('Pro_local_amount')
                        }, {
                            field: 'pro_amount',
                            title: __('Pro_amount')
                        }, {
                            field: 'pro_cost',
                            title: __('Pro_cost')
                        }, {
                            field: 'dept_name',
                            title: __('Deduct_addr')
                        }, {
                            field: 'dept_id',
                            title: __('Dept_id'),
                            formatter: yjyApi.formatter.dept_name
                        },
                        // {field: 'pro_fee_type', title: __('Pro_fee_type'), formatter:yjyApi.formatter.fee},
                        {
                            field: 'pro_fee_type_name',
                            title: __('Pro_fee_type')
                        },
                        // {field: 'pro_deadline', title: __('Pro_deadline')},
                        // {
                        //     field: 'allow_position_bonus',
                        //     title: __('Allow_position_bonus'),
                        //     formatter: Backend.api.formatter.status
                        // }, {
                        //     field: 'allow_consult_calc',
                        //     title: __('Allow_consult_calc'),
                        //     formatter: Backend.api.formatter.status
                        // },
                        {
                            field: 'deduct_switch',
                            title: __('Deduct_switch'),
                            formatter: Backend.api.formatter.status,
                        },
                        // {
                        //     field: 'allow_bonus',
                        //     title: __('Allow_bonus'),
                        //     formatter: function(value) {
                        //         return value ? '<i class="fa fa-check text-success"></i>' : '';
                        //     },
                        // },
                        {
                            field: 'pro_status',
                            title: __('Pro_status'),
                            formatter: Backend.api.formatter.status
                        },
                        {
                            field: 'pro_remark',
                            title: __('Pro_remark'),
                            formatter: function(value) {
                                return Backend.api.formatter.content(value, null, null);
                            },
                        }
                        // {
                        //     field: 'operate',
                        //     title: __('Operate'),
                        //     table: table,
                        //     events: Table.api.events.operate,
                        //     formatter: Table.api.formatter.operate,
                        //     cellStyle: function(value, row, index) {
                        //         return {
                        //             css: {
                        //                 "word-wrap": "no-wrap",
                        //             }
                        //         };
                        //     }
                        // }
                    ]
                ];
             if (username == 'developer') {
                rCols[0].push({
                        field: 'operate',
                        title: __('Operate'),
                        table: table,
                        events: Table.api.events.operate,
                        formatter: Table.api.formatter.operate,
                        cellStyle: function(value, row, index) {
                            return {
                                css: {
                                    "word-wrap": "no-wrap",
                                }
                            };
                        }
                    });
            }
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'pro_id',
                search: false,
                sortName: 'pro.pro_id',
                commonSearch: false,
                search: false,
                columns: rCols,
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
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            $(document).on("change", "select[name='pro_cat1']", function() {
                var cate = $('[name="pro_cat1"]').val();
                var tArg = arguments;
                $.ajax({
                    url: "base/project/getLv2Cate",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        cate_id: cate
                    },
                    success: function(data) {
                        $('[name="pro_cat2"]').html('');
                        sortData = Object.keys(data);
                        sortData.sort();
                        for (var i in sortData) {
                            $('[name="pro_cat2"]').append('<option value="' + sortData[i] + '">' + data[sortData[i]] + '</option>');
                        }
                    }
                });
            })
            $('.btn-default').on('click', function() {
                $('[name="pro_cat2"]').html('');
                $('[name="pro_cat2"]').append('<option value=""></option>');
            })
            // 为表格绑定事件
            $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
            $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                $('.commonsearch-table').toggleClass('hidden');
            });
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    pro_code: '=',
                    pro_name: 'LIKE %...%',
                    pro_spec: 'LIKE %...%',
                    'pro.dept_id': '=',
                    pro_fee_type: '=',
                    pro_cat1: '=',
                    pro_cat2: '=',
                    pro_status: '=',
                    'fpro.status': '=',
                });
            });

            //导出
            $('#btn-export').on('click', function() {
                var url = '/base/Project/downloadprocess' + '?op=' + encodeURI(currentOp) + '&filter=' + encodeURI(currentFilter) ;
                Fast.api.open(url, __('Downloading page'));
            });
        },

        downloadprocess: function() {
            return Backend.api.commondownloadprocess('/base/Project/downloadprocess');
        },
        add: function() {
            Controller.api.bindevent();
            $('.btn-set-spell').each(function() {
                $(this).on('click', function() {
                    var target = $($(this).data('target'));
                    $("input[name='row[pro_spell]']").val(pinyin.getCamelChars(target.val()));
                })
            })
            changeCate();
        },
        edit: function() {
            Controller.api.bindevent();
            $('.btn-set-spell').each(function() {
                $(this).on('click', function() {
                    var target = $($(this).data('target'));
                    $("input[name='row[pro_spell]']").val(pinyin.getCamelChars(target.val()));
                })
            })
            var cate2 = $('[name="row[pro_cat2]"]').val();
            var cate3 = $('[name="row[pro_cat3]"]').val();
            changeCate(cate2, cate3);
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
                    pro_id: '=',
                    pro_name: 'LIKE %...%',
                    pro_code: 'LIKE %...%',
                    createtime: 'BETWEEN',
                    pro_spec: 'LIKE %...%',
                });
            });
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
                $(document).on("change", "select[name='row[pro_cat1]']", function() {
                    changeCate();
                })
                $(document).on("change", "select[name='row[pro_cat2]']", function() {
                    changeCate1();
                })
            }
        }
    };

    function changeCate() {
        var cate = $('[name="row[pro_cat1]"]').val();
        var tArg = arguments;
        $.ajax({
            url: "base/project/getLv2Cate",
            type: 'post',
            dataType: 'json',
            data: {
                cate_id: cate
            },
            success: function(data) {
                $('[name="row[pro_cat2]"]').html('');
                for (var i in data) {
                    $('[name="row[pro_cat2]"]').append('<option value="' + i + '">' + data[i] + '</option>');
                }
                if (tArg.length >= 2) {
                    var initValue = tArg[0];
                    $('[name="row[pro_cat2]"]').val(initValue);
                    changeCate1(tArg[1]);
                } else {
                    changeCate1();
                }
            }
        });
    }

    function changeCate1() {
        var cate = $('[name="row[pro_cat2]"]').val();
        var tArg = arguments;
        $.ajax({
            url: "base/project/getLv2Cate",
            type: 'post',
            dataType: 'json',
            data: {
                cate_id: cate
            },
            success: function(data) {
                $('[name="row[pro_cat3]"]').html('');
                for (var i in data) {
                    $('[name="row[pro_cat3]"]').append('<option value="' + i + '">' + data[i] + '</option>');
                }
                if (tArg.length > 0) {
                    $('[name="row[pro_cat3]"]').val(tArg[0]);
                }
            }
        });
    }
    $('#status-switch').bootstrapSwitch({
        onText: "正常",
        offText: "禁用",
        onColor: "success",
        offColor: "danger",
        size: "small",
        //初始开关状态
        state: $('#c-pro_status').val() == 1 ? true : false,
        onSwitchChange: function(event, state) {
            if (state == true) {
                $('#c-pro_status').val(1);
            } else {
                $('#c-pro_status').val(0);
            }
        }
    })
    $('#zhiwei-switch').bootstrapSwitch({
        onText: "是",
        offText: "否",
        onColor: "success",
        offColor: "danger",
        size: "small",
        //初始开关状态
        state: $('#c-allow_position_bonus').val() == 1 ? true : false,
        onSwitchChange: function(event, state) {
            if (state == true) {
                $('#c-allow_position_bonus').val(1);
            } else {
                $('#c-allow_position_bonus').val(0);
            }
        }
    })
    $('#jszx-switch').bootstrapSwitch({
        onText: "是",
        offText: "否",
        onColor: "success",
        offColor: "danger",
        size: "small",
        //初始开关状态
        state: $('#c-allow_consult_calc').val() == 1 ? true : false,
        onSwitchChange: function(event, state) {
            if (state == true) {
                $('#c-allow_consult_calc').val(1);
            } else {
                $('#c-allow_consult_calc').val(0);
            }
        }
    })
    $('#kshq-switch').bootstrapSwitch({
        onText: "是",
        offText: "否",
        onColor: "success",
        offColor: "danger",
        size: "small",
        //初始开关状态
        state: $('#c-deduct_switch').val() == 1 ? true : false,
        onSwitchChange: function(event, state) {
            if (state == true) {
                $('#c-deduct_switch').val(1);
            } else {
                $('#c-deduct_switch').val(0);
            }
        }
    })
    $('#jfzs-switch').bootstrapSwitch({
        onText: "是",
        offText: "否",
        onColor: "success",
        offColor: "danger",
        size: "small",
        //初始开关状态
        state: $('#c-allow_bonus').val() == 1 ? true : false,
        onSwitchChange: function(event, state) {
            if (state == true) {
                $('#c-allow_bonus').val(1);
            } else {
                $('#c-allow_bonus').val(0);
            }
        }
    })
    var yjyApi = {
        formatter: {
            dept_name: function(value) {
                if (value > 0) {
                    return __('dept_name_' + value);
                }
            },
            fee: function(value) {
                return __('fee_' + value);
            },
        }
    };
    return Controller;
});