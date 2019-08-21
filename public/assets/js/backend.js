define(['fast', 'moment'], function(Fast, Moment) {
    var Backend = {
        api: {
            sidebar: function(params) {
                colorArr = ['red', 'green', 'yellow', 'blue', 'teal', 'orange', 'purple'];
                $colorNums = colorArr.length;
                badgeList = {};
                $.each(params, function(k, v) {
                    $url = Fast.api.fixurl(k);
                    if ($.isArray(v)) {
                        $nums = typeof v[0] !== 'undefined' ? v[0] : 0;
                        $color = typeof v[1] !== 'undefined' ? v[1] : colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                        $class = typeof v[2] !== 'undefined' ? v[2] : 'label';
                    } else {
                        $nums = v;
                        $color = colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                        $class = 'label';
                    }
                    //必须nums大于0才显示
                    badgeList[$url] = $nums > 0 ? '<small class="' + $class + ' pull-right bg-' + $color + '">' + $nums + '</small>' : '';
                });
                $.each(badgeList, function(k, v) {
                    var anchor = top.window.$("li a[addtabs][url='" + k + "']");
                    if (anchor) {
                        top.window.$(".pull-right-container", anchor).html(v);
                        top.window.$(".nav-addtabs li a[node-id='" + anchor.attr("addtabs") + "'] .pull-right-container").html(v);
                    }
                });
            },
            addtabs: function(url, title, icon) {
                var dom = "a[url='{url}']"
                var leftlink = top.window.$(dom.replace(/\{url\}/, url));
                if (leftlink.size() > 0) {
                    leftlink.trigger("click");
                } else {
                    url = Fast.api.fixurl(url);
                    leftlink = top.window.$(dom.replace(/\{url\}/, url));
                    if (leftlink.size() > 0) {
                        var event = leftlink.parent().hasClass("active") ? "dblclick" : "click";
                        leftlink.trigger(event);
                    } else {
                        var baseurl = url.substr(0, url.indexOf("?") > -1 ? url.indexOf("?") : url.length);
                        leftlink = top.window.$(dom.replace(/\{url\}/, baseurl));
                        //能找到相对地址
                        if (leftlink.size() > 0) {
                            icon = typeof icon !== 'undefined' ? icon : leftlink.find("i").attr("class");
                            title = typeof title !== 'undefined' ? title : leftlink.find("span:first").text();
                            leftlink.trigger("fa.event.toggleitem");
                        }
                        var navnode = $(".nav-tabs ul li a[node-url='" + url + "']");
                        if (navnode.size() > 0) {
                            navnode.trigger("click");
                        } else {
                            //追加新的tab
                            var id = Math.floor(new Date().valueOf() * Math.random());
                            icon = typeof icon !== 'undefined' ? icon : 'fa fa-circle-o';
                            title = typeof title !== 'undefined' ? title : '';
                            top.window.$("<a />").append('<i class="' + icon + '"></i> <span>' + title + '</span>').prop("href", url).attr({
                                url: url,
                                addtabs: id
                            }).addClass("hide").appendTo(top.window.document.body).trigger("click");
                        }
                    }
                }
            },
            initYjySwitcher: function() {
                $('.yjy-switch').each(function() {
                    let statusId = $(this).attr('id');
                    let targetId = 'c-' + statusId.replace('-switch', '');
                    let onText = $(this).data('onText') ? $(this).data('onText') : "正常";
                    let offText = $(this).data('offText') ? $(this).data('offText') : "禁用";
                    let onColor = $(this).data('onColor') ? $(this).data('onColor') : "success";
                    let offColor = $(this).data('offColor') ? $(this).data('offColor') : "danger";
                    $(this).bootstrapSwitch({
                        onText: onText,
                        offText: offText,
                        onColor: onColor,
                        offColor: offColor,
                        size: "small",
                        //初始开关状态
                        state: $('#' + targetId).val() == 1 ? true : false,
                        onSwitchChange: function(event, state) {
                            if (state == true) {
                                $('#' + targetId).val(1);
                            } else {
                                $('#' + targetId).val(0);
                            }
                        }
                    });
                });
            },
            formatter: {
                status: function(value, row, index, custom) {
                    //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                    var colorArr = {
                        1: 'success',
                        0: 'danger'
                    };
                    //如果有自定义状态,可以按需传入
                    if (typeof custom !== 'undefined') {
                        colorArr = $.extend(colorArr, custom);
                    }
                    value = value.toString();
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    value = value.charAt(0).toUpperCase() + value.slice(1);
                    //渲染状态
                    var html = '<span class="text-' + color + '"><i class="fa fa-circle"></i></span>';
                    return html;
                },
                date: function(value, row, index) {
                    return value && value != "0" ? Moment(parseInt(value) * 1000).format("YYYY-MM-DD") : __('None');
                },
                datetime: function(value, row, index) {
                    return value && value != "0" ? Moment(parseInt(value) * 1000).format("YYYY-MM-DD HH:mm:ss") : __('None');
                },
                operate: function(value, row, index) {
                    var table = this.table;
                    // 操作配置
                    var options = table ? table.bootstrapTable('getOptions') : {};
                    // 默认按钮组
                    var buttons = $.extend([], this.buttons || []);
                    buttons.push({
                        name: 'edit',
                        icon: 'fa fa-pencil',
                        classname: 'btn btn-xs btn-success btn-editone'
                    });
                    buttons.push({
                        name: 'del',
                        icon: 'fa fa-trash',
                        classname: 'btn btn-xs btn-danger btn-delone'
                    });
                    var html = [];
                    $.each(buttons, function(i, j) {
                        var attr = table.data("operate-" + j.name);
                        if ((typeof attr === 'undefined' || attr) || (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] == 'undefined')) {
                            if (['add', 'edit', 'del', 'multi'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
                                return true;
                            }
                            //自动加上ids
                            j.url = j.url ? j.url + (j.url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] : '';
                            url = j.url ? Fast.api.fixurl(j.url) : 'javascript:;';
                            classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
                            icon = j.icon ? j.icon : '';
                            text = j.text ? j.text : '';
                            title = j.title ? j.title : text;
                            html.push('<a href="' + url + '" class="' + classname + '" title="' + title + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
                        }
                    });
                    return html.join(' ');
                },
                content: function(value, row, index, limitLength) {
                    if (value == null) {
                        return '';
                    }
                    var shortContent = value;
                    var limitLength = limitLength ? limitLength : 50;
                    if (value.length > limitLength) {
                        shortContent = subString(value, limitLength);
                    }
                    return '<span data-toggle="tooltip" title="' + value + '">' + shortContent + '</span>'
                },
                pic: function(value) {
                    return value ? `<img src="${value}" style="max-width: 100px; max-height: 100px;" />` : '';
                },
            },
            initYjyCommonSearch: function(table, columnOps) {
                $('.fixed-table-toolbar').prepend('<div class="columns-right pull-right" style="margin-top:10px;"><button class="btn btn-default" type="button" name="commonSearch" title="普通搜索"><i class="glyphicon glyphicon-search"></i></button></div>');
                $('.fixed-table-toolbar [name="commonSearch"]').on('click', function() {
                    $('.commonsearch-table').toggleClass('hidden');
                });
                // 搜索表单提交
                $("form.form-commonsearch").off('submit').on("submit", function(event) {
                    event.preventDefault();
                    return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                        osc_id: '=',
                        osc_status: '=',
                        osc_type: '=',
                        updatetime: 'BETWEEN',
                    });
                });
            },
            yjyGenerateParams: function(form, columnOps) {
                var form = $(form);
                var filter = {};
                for (var i in columnOps) {
                    if (columnOps[i] == 'BETWEEN') {
                        var startEle = form.find('[name="' + i + '_start' + '"]');
                        var endEle = form.find('[name="' + i + '_end' + '"]');
                        if (startEle.val() != '' || endEle.val() != '') {
                            if (startEle.hasClass('datetimepicker')) {
                                var value_begin = startEle.val() == '' ? '1970-01-01' : startEle.val();
                                var value_end = endEle.val() == '' ? '2286-10-01' : endEle.val();
                                if (!startEle.hasClass('forbid-timestamp')) {
                                    var Hms = Moment(value_end).format("HH:mm:ss");
                                    value_begin = parseInt(Moment(value_begin) / 1000);
                                    value_end = parseInt(Moment(value_end) / 1000);
                                    // if (value_begin === value_end && '00:00:00' === Hms) {
                                    //     //一天秒数-1
                                    //     value_end += 86399;
                                    // }
                                    //结尾时间如为0， 自动变为23:59:59
                                    if (Hms == '00:00:00') {
                                        value_end += 86399;
                                    }
                                }
                                // var Hms = Moment(value_end).format("HH:mm:ss");
                                // value_begin = parseInt(Moment(value_begin) / 1000);
                                // value_end = parseInt(Moment(value_end) / 1000);
                                // // if (value_begin === value_end && '00:00:00' === Hms) {
                                // //     //一天秒数-1
                                // //     value_end += 86399;
                                // // }
                                // //结尾时间如为0， 自动变为23:59:59
                                // if (Hms == '00:00:00') {
                                //     value_end += 86399;
                            } else {
                                var value_begin = startEle.val() == '' ? 0 : startEle.val();
                                var value_end = endEle.val() == '' ? '9999999999' : endEle.val();
                            }
                            filter[i] = value_begin + ',' + value_end
                        }
                        // else {
                        //     var value_begin = startEle.val() == '' ? 0 : startEle.val();
                        //     var value_end = endEle.val() == '' ? '9999999999' : endEle.val();
                        // }
                        // filter[i] = value_begin + ',' + value_end
                        // }
                    } else {
                        var targetEle = form.find('[name="' + i + '"]');
                        if (targetEle.val() != '') {
                            filter[i] = targetEle.val();
                        }
                    }
                }
                return filter;
            },
            //columns  = {osc_id: '=', ...}
            //form, table == selector or dom or jquery dom
            yjyCommonSearch: function(form, table, columnOps) {
                // 追加查询关键字
                var filter = {};
                var op = columnOps;
                var form = $(form);
                for (var i in columnOps) {
                    if (columnOps[i] == 'BETWEEN') {
                        var startEle = form.find('[name="' + i + '_start' + '"]');
                        var endEle = form.find('[name="' + i + '_end' + '"]');
                        if (startEle.val() != '' || endEle.val() != '') {
                            if (startEle.hasClass('datetimepicker')) {
                                var value_begin = startEle.val() == '' ? '1970-01-01' : startEle.val();
                                var value_end = endEle.val() == '' ? '2286-10-01' : endEle.val();
                                if (!startEle.hasClass('forbid-timestamp')) {
                                    var Hms = Moment(value_end).format("HH:mm:ss");
                                    value_begin = parseInt(Moment(value_begin) / 1000);
                                    value_end = parseInt(Moment(value_end) / 1000);
                                    // if (value_begin === value_end && '00:00:00' === Hms) {
                                    //     //一天秒数-1
                                    //     value_end += 86399;
                                    // }
                                    //结尾时间如为0， 自动变为23:59:59
                                    if (Hms == '00:00:00') {
                                        value_end += 86399;
                                    }
                                }
                            } else {
                                var value_begin = startEle.val() == '' ? 0 : startEle.val();
                                var value_end = endEle.val() == '' ? '9999999999' : endEle.val();
                            }
                            filter[i] = value_begin + ',' + value_end
                        }
                    } else {
                        var targetEle = form.find('[name="' + i + '"]');
                        if (targetEle.length > 1) {
                            filter[i] = form.find('[name="' + i + '"]:checked').val();
                        } else {
                            if (targetEle.val() != '') {
                                filter[i] = targetEle.val();
                            }
                        }
                    }
                }
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function(params) {
                    return {
                        search: params.search,
                        sort: params.sort,
                        order: params.order,
                        filter: JSON.stringify(filter),
                        op: JSON.stringify(op),
                        offset: params.offset,
                        limit: params.limit,
                    };
                };
                //更新搜索等信息，保证分页等正确
                table.bootstrapTable('refreshOptions', options);
                //重新获取数据并刷新显示
                table.bootstrapTable('refresh', {
                    query: {
                        filter: JSON.stringify(filter),
                        op: JSON.stringify(op)
                    }
                });
                return false;
            },
            commondownloadprocess: function(processUrl) {
                //进度更新
                var recId = $('#h-record-id').val();
                if ($('#btn-download').hasClass('hidden')) {
                    //清除下载命令
                    $('#btn-del-download').on('click', function() {
                        var recId = $('#h-record-id').val();
                        var confirmIndex = layer.confirm(__('Are you sure to delete?'), function(index, layero) {
                            layer.load();
                            $.ajax({
                                url: processUrl,
                                dataType: 'json',
                                type: 'post',
                                data: {
                                    id: recId,
                                    delete: true
                                },
                                success: function(data) {
                                    layer.closeAll('loading');
                                    layer.msg(__('Operation completed'), {
                                        icon: 1
                                    });
                                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                                },
                                error: function() {
                                    layer.closeAll('loading');
                                    layer.msg(__('Error occurs'), {
                                        icon: 2
                                    });
                                },
                            });
                        });
                    });
                    var currentCompleteCount = -2;
                    var currentFreezeTimes = 0;
                    var processInterval = setInterval(function() {
                        $.ajax({
                            url: processUrl,
                            dataType: 'json',
                            data: {
                                id: recId
                            },
                            type: 'post',
                            success: function(data) {
                                if (typeof data.status == 'undefined') {
                                    var msg = __('Error occurs');
                                    if (typeof data.msg != 'undefined') {
                                        msg = __(data.msg);
                                    }
                                    layer.msg(msg, {
                                        icon: 2
                                    });
                                    return false;
                                }
                                var completedCount = parseInt(data.completedCount);
                                var total = parseInt(data.total);
                                if (total) {
                                    var percent = (100.00 * 　completedCount / total).toFixed(2);
                                } else {
                                    var percent = 100.00;
                                }
                                $('#statusText').html(__(data.statusText));
                                $('.progress-bar').css('width', percent + '%');
                                $('.progress-bar span').text(completedCount + '/' + total + '(' + percent + '%)');
                                if (data.status == 'COMPLETED') {
                                    $('#btn-download').removeClass('hidden');
                                    $('#btn-regenerate').removeClass('hidden');
                                    clearInterval(processInterval);
                                } else if (data.status == 'FAILED') {
                                    $('#btn-del-download').removeClass('hidden');
                                    $('#btn-regenerate').removeClass('hidden');
                                    clearInterval(processInterval);
                                } else if (data.status == 'ABORT') {
                                    $('#btn-regenerate').removeClass('hidden');
                                    clearInterval(processInterval);
                                } else {
                                    if (currentCompleteCount == completedCount) {
                                        if ((++currentFreezeTimes) >= 5) {
                                            $('#btn-del-download').removeClass('hidden');
                                        }
                                    } else {
                                        currentCompleteCount = completedCount;
                                        currentFreezeTimes = 0;
                                        $('#btn-del-download').addClass('hidden');
                                    }
                                }
                            },
                            error: function() {
                                clearInterval(processInterval);
                                layer.msg(__('Error occurs'), {
                                    icon: 2
                                });
                            },
                        })
                    }, 1200);
                }
                //重新生成
                $('#btn-regenerate').on('click', function() {
                    var recId = $('#h-record-id').val();
                    var confirmIndex = layer.confirm(__('Are you sure to regenerate?'), function(index, layero) {
                        layer.load();
                        $.ajax({
                            url: processUrl,
                            dataType: 'json',
                            type: 'post',
                            data: {
                                id: recId,
                                force: true
                            },
                            success: function(data) {
                                layer.closeAll('loading');
                                layer.msg(__('Operation completed'), {
                                    icon: 1
                                });
                                window.location.reload();
                            },
                            error: function() {
                                layer.closeAll('loading');
                                layer.msg(__('Error occurs'), {
                                    icon: 2
                                });
                            },
                        });
                    });
                });
            },
        },
        //客服历史， 网络客服历史 Table 实际为 require-table
        initConsultHistory: function(tableSelector, idSelector, Table) {
            var maxWidth = ($(window).width() * 0.15) > 180 ? ($(window).width() * 0.15) + 'px' : '180px';
            var ids = $(idSelector).val();
            var conTable = $(tableSelector);
            if (conTable.length == 0) {
                return false;
            }
            //cst_status 0未预约， 1预约， 2已到诊， 3已过时
            // 初始化表格
            Table.api.init();
            conTable.bootstrapTable({
                url: 'customer/customer/getcsthistory/ids/' + ids,
                pk: 'cst_id',
                sortName: 'cst_id',
                sortOrder: 'desc',
                search: false,
                toolbar: "",
                commonSearch: false,
                onLoadSuccess: function(data) {
                    //提示工具
                    // $("[data-toggle='tooltip']").tooltip();
                },
                columns: [
                    [
                        // {checkbox: true},
                        {
                            field: 'cst_id',
                            title: __('Cst_id')
                        }, {
                            field: 'customer_id',
                            title: __('ctm_id')
                        }, {
                            field: 'tool_id',
                            title: __('tool_id'),
                            formatter: function(value, row, index) {
                                if (value) {
                                    return __('accept_tool_' + value);
                                } else {
                                    return '';
                                }
                            }
                        }, {
                            field: 'admin_nickname',
                            title: __('cst_Admin_nickname')
                        }, {
                            field: 'cpdt_name',
                            title: __('cpdt_name')
                        },
                        // {field: 'cst_status', title: __('Cst_status'), formatter: Backend.api.formatter.status},
                        // { field: 'fat_name', title: __('Fat_id'), formatter: Backend.api.formatter.content },
                        {
                            field: 'cst_content',
                            title: __('Cst_content'),
                            // formatter: Backend.api.formatter.content,
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        'width': '180px',
                                        'max-width': maxWidth,
                                        "word-wrap": "break-word",
                                        "text-align": "left !important",
                                    }
                                };
                            }
                        }, {
                            field: 'createtime',
                            title: __('Yjy cst ctime'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'book_time',
                            title: __('Book_time'),
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'cst_status',
                            title: __('Cst_status'),
                            formatter: function(value) {
                                var text = '';
                                var cssCls = '';
                                if (value == 0) {
                                    text = '<a class="btn btn-xs btn-primary" href="javascript:;"><i class="fa fa-arrow-right"></i></a> ' + __('Status_ng');
                                } else if (value == 1) {
                                    text = '<a class="btn btn-xs btn-primary" href="javascript:;"><i class="fa fa-arrow-right"></i></a> ' + __('Status_pending');
                                } else if (value == 2) {
                                    text = '<a class="btn btn-xs btn-success" href="javascript:;"><i class="fa fa-check"></i></a> ' + __('Status_success');
                                } else if (value == 3) {
                                    text = '<a class="btn btn-xs btn-danger" href="javascript:;"><i class="fa fa-times"></i></a> ' + __('Status_outdate');
                                } else {
                                    text = '--';
                                }
                                return text;
                            }
                        },
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
        },
        initOsconsultHistory: function(tableSelector, idSelector, Table) {
            var osconTable = $(tableSelector);
            if (osconTable.length == 0) {
                return false;
            }
            var ids = $(idSelector).val();
            //cst_status 0未预约， 1预约， 2已到诊， 3已过时
            // 初始化表格
            Table.api.init();
            osconTable.bootstrapTable({
                //关闭通用查询
                commonSearch: false,
                search: false,
                pk: 'osc_id',
                searchOnEnterKey: false,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                },
                url: 'customer/customer/getcochistory/ids/' + ids,
                pk: 'osc_id',
                sortName: 'osc_id',
                sortOrder: 'DESC',
                buttons: [{
                    name: 'deny',
                    icon: 'fa fa-pencil',
                    classname: 'btn btn-xs btn-success btn-editone'
                }, ],
                columns: [
                    [{
                            field: 'osc_status',
                            title: __('Osc_status'),
                            formatter: function(value, row, index) {
                                if ((value = parseInt(value)) == NaN) {
                                    return '--';
                                }
                                $key = 'Status_';
                                if (value < 0) {
                                    $key += 'm_';
                                }
                                $key = $key + Math.abs(value);
                                return __($key);
                            },
                        }, {
                            field: 'createtime',
                            title: '分诊时间',
                            formatter: Table.api.formatter.datetime
                        }, {
                            field: 'osc_type',
                            title: __('Osc_type'),
                            formatter: function(value) {
                                var value = parseInt(value);
                                if (value == NaN || value == 0) {
                                    return '-';
                                }
                                return __('osc_type_' + value);
                            },
                        }, {
                            field: 'ctm_name',
                            title: __('Ctm_name'),
                            cellStyle: {
                                css: {
                                    "word-break": "keep-all"
                                }
                            }
                        }, {
                            field: 'ctm_id',
                            title: __('Ctm_id')
                        },
                        // {field: 'osc_id', title: __('Osc_id')},
                        // {field: 'consult_admin', title: __('Consult_admin')},
                        {
                            field: 'admin_name',
                            title: __('coc_Admin_nickname')
                        }, {
                            field: 'service_admin_name',
                            title: '导医'
                        }, {
                            field: 'operator_name',
                            title: __('Operator')
                        }, {
                            field: 'cpdt_name',
                            title: __('cpdt_name')
                        },
                        // {field: 'osc_content', title: __('Osc_content')},
                        {
                            field: 'osc_content',
                            title: __('Osc_content'),
                            // formatter: Backend.api.formatter.content
                        }, {
                            field: 'osc_status',
                            title: __('Osc_status'),
                            formatter: function(value, row, index) {
                                if ((value = parseInt(value)) == NaN) {
                                    return '--';
                                }
                                $key = 'Status_';
                                if (value < 0) {
                                    $key += 'm_';
                                }
                                $key = $key + Math.abs(value);
                                return __($key);
                            },
                        },
                    ]
                ]
            });
        },
        initOrderItemsHistory: function(tableSelector, idSelector, Table, useAdminFilter, deduct) {
            var orderTable = $(tableSelector);
            if (orderTable.length == 0) {
                return false;
            }
            var ids = $(idSelector).val();
            //初始化表格
            Table.api.init({
                extend: {
                    pay_url: 'cash/balance/payorder',
                }
            });
            url = 'cash/order/adminfilteredlist2?customer_id=' + ids;
            var rColumn = [
                [{
                    field: 'item_id',
                    title: __('No.'),
                    formatter: function(value, row, index) {
                        return '<span class="flg_s" data-item_id="' + row.item_id + '">' + (index + 1) + '</span>';
                    }
                }, {
                    field: 'operate',
                    title: __('Operate'),
                    formatter: function(value, row, index) {
                        // var str = '<a href="javascript:;" style="margin-right: 5px;" class="btn btn-xs btn-success btn-editone" title="' + __('Edit') + '"><i class="fa fa-pencil"></i></a>';
                        var str = '';
                        if (row.item_status == 1) {
                            if (row.item_total_times != row.item_used_times) {
                                 // text-warning
                                str += '<a href="javascript:;" class="btn btn-xs btn-default btn-cancel-switch" title="' + __('Cancel/Switch Item') + '"><i class="fa fa-refresh">退换</i></a>';
                            }
                        } else if (row.item_status == 0) {
                             // text-danger
                            str += '<a href="javascript:;" class="btn btn-xs btn-default btn-cancel"><i class="fa fa-trash"></i>撤单</a>';
                            // str += '';
                        }
                        return str;
                    },
                    cellStyle: function(value) {
                        return {
                            css: {
                                "white-space": "nowrap",
                            }
                        }
                    },
                    events: {
                        'click .btn-cancel-switch': function(e, value, row, index) {
                            e.stopPropagation();
                            top.Fast.api.open('/cash/order/switchitem/ids/' + row.item_id, __('Cancel/Switch Item'));
                        },
                        'click .btn-editone': function(e, value, row, index) {
                            e.stopPropagation();
                            top.Fast.api.open('/cash/order/edit/ids/' + row.item_id, __('Edit'));
                        },
                        'click .btn-cancel': function(e, value, row, index) {
                            e.stopPropagation();
                            var index = Layer.confirm(__('确定取消此订单吗?'), {
                                icon: 3,
                                title: __('Warning'),
                                shadeClose: true
                            }, function(index, layero) {
                                layer.load();
                                $.ajax({
                                    url: 'cash/order/cancelOwnOrder',
                                    dataType: 'json',
                                    type: 'post',
                                    data: {
                                        ids: row['item_id'],
                                    },
                                    success: function(data) {
                                        layer.closeAll('loading');
                                        if (data.code == '1') {
                                            layer.msg(__('Operation completed'), {
                                                icon: 1
                                            });
                                            window.location.reload();
                                        } else {
                                            var msg = data.msg ? data.msg : '操作失败';
                                            layer.msg(msg, {
                                                icon: 2
                                            });
                                        }
                                    },
                                    error: function() {
                                        layer.closeAll('loading');
                                        layer.msg(__('Error occurs'), {
                                            icon: 2
                                        });
                                    },
                                });
                            });
                        },
                    },
                }, {
                    field: 'item_status',
                    title: __('order_status'),
                    formatter: function(value, row) {
                        let statusStr = __('order_status_' + (value >= 0 ? value : 'm_' + Math.abs(value)));
                        let finalStr = '<i class="fa fa-spinner" style="opacity: .8">' + statusStr + '</i>';
                        if (value == 1) {
                            finalStr = '<i class="fa fa-play text-success">' + statusStr + '</i>';
                        } else {
                            if (value == 2) {
                                finalStr = '<i class="fa fa-stop text-danger">' + statusStr + '</i>';
                            }
                        }
                        return finalStr;
                    },
                }, {
                    field: 'pro_name',
                    title: __('pro_name'),
                    formatter: function(value) {
                        return '<a href="javascript:;" class="btn-view-deduct"><i class="fa fa-search"></i> ' + value + '</a>';
                    },
                    events: {
                        'click .btn-view-deduct': function(e, value, row, index) {
                            e.stopPropagation();
                            orderTable.find('tbody .deepShow').removeClass('deepShow');
                            $(e.currentTarget).parents('tr').addClass('deepShow');
                            Backend.initDeductTable('#h-deducted-table', Table, row.item_id);
                        }
                    },
                    cellStyle: function(value) {
                        return {
                            css: {
                                'width': '120px',
                                'min-width': '120px',
                                "word-wrap": "normal",
                                'text-align': 'left',
                            }
                        }
                    },
                }, {
                    field: 'pro_spec',
                    title: __('pro_spec'),
                    cellStyle: function(value) {
                        return {
                            css: {
                                'width': '120px',
                                'min-width': '120px',
                                "word-wrap": "normal",
                                'text-align': 'left',
                            }
                        };
                    },
                }, {
                    field: 'item_used_times',
                    title: __('item_used_times'),
                }, {
                    field: 'item_total_times',
                    title: __('item_total_times'),
                }, {
                    field: 'item_ori_total',
                    title: __('item_ori_total'),
                }, {
                    field: 'item_total',
                    title: __('item_total'),
                }, {
                    field: 'item_pay_total',
                    title: __('item_pay_total'),
                    formatter: function(value, index, row) {
                        return '<span style="color: #72afd2">' + value + '</span>';
                    }
                }, {
                    field: 'item_coupon_total',
                    title: __('item_coupon_total'),
                }, {
                    field: 'item_paytime',
                    title: __('item_paytime'),
                    formatter: function(value, row) {
                        if (value > 0) {
                            return Backend.api.formatter.date(value, null, null);
                        }
                    },
                    cellStyle: function(value) {
                        return {
                            css: {
                                "white-space": "nowrap",
                            }
                        };
                    },
                }, {
                    field: 'admin_id',
                    title: __('coc_Admin_nickname'),
                }, {
                    field: 'prescriber_name',
                    title: __('prescriber_name'),
                }, ]
            ];
            orderTable.bootstrapTable({
                //关闭通用查询
                url: url,
                pk: 'item_id',
                sortName: 'item_id',
                sortOrder: 'DESC',
                search: false,
                commonSearch: false,
                escape: false,
                height: '100%',
                pageSize: 50,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    if (data.summary) {
                        //支付额
                        $('.his-item_pay_total').text(data.summary.item_pay_total);
                        //券额
                        $('.his-item_coupon_total').text(data.summary.item_coupon_total);
                        //折后总额
                        $('.his-item_total').text(data.summary.item_total);
                    }
                },
                columns: rColumn,
            });
            orderTable.removeClass('table-hover');
            //开手术单，开处方单，开物资单
            $('#btn-createprojectorder').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/order/createprojectorder' + '&field=customer_id&title=' + __('Create order');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-createrecipeorder').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/order/createrecipeorder' + '&field=customer_id&title=' + __('Create order');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-createproductorder').on('click', function() {
                var params = '?mode=redirect&url=' + 'cash/order/createproductorder' + '&field=customer_id&title=' + __('Create order');
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
        },
        initHMHistory: function(tableSelector, idSelector, Table) {
            var hmHistoryTable = $(tableSelector);
            if (hmHistoryTable.length == 0) {
                return false;
            }
            var ids = $(idSelector).val();
            //初始化表格
            Table.api.init({});
            hmHistoryTable.bootstrapTable({
                url: 'customer/customer/viewhmhistory/ids/' + ids,
                pk: 'ctp_id',
                search: false,
                toolbar: "",
                sortName: 'ctp_id',
                sortOrder: 'desc',
                commonSearch: false,
                columns: [
                    [{
                        field: 'yjy_product_name',
                        title: __('hm_product_name'),
                        formatter: function(value, row, index) {
                            return Backend.api.formatter.content(value, row, index, 12);
                        }
                    }, {
                        field: 'pid_date',
                        title: '日期',
                    }, {
                        field: 'ctp_oldnum',
                        title: __('hm_ctp_oldnum'),
                        formatter: function(value, row, index) {
                            return row['ctp_oldnum'] ? row['ctp_oldnum'] : row['ctp_selnum'];
                        },
                    }, {
                        field: 'cpy_account',
                        title: __('hm_cpy_account'),
                    }, {
                        field: 'cpy_pay',
                        title: __('hm_cpy_pay'),
                    }, {
                        field: 'yjy_developer_name',
                        title: __('yjy_developer_name'),
                    }, {
                        field: 'yjy_recepter_name',
                        title: __('recept_admin_name'),
                    }, {
                        field: 'yjy_osconsulter_name',
                        title: __('osconsult_admin_name'),
                    }, ]
                ],
                //              searchOnEnterKey: false,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                    if (data.summary) {
                        // $('.hm_real_total').text(data.summary.real_total);
                        $('.hm_cpy_account_total').text(data.summary.cpy_account_total);
                        $('.hm_cpy_pay_total').text(data.summary.cpy_pay_total);
                    }
                },
            });
        },
        //回访记录
        initRvinfoHistory: function(tableSelector, idSelector, Table) {
            var rvinfoTable = $(tableSelector);
            if (rvinfoTable.length == 0) {
                return false;
            }
            var ids = $(idSelector).val();
            // 初始化表格
            Table.api.init({
                classes: 'table'
            });
            var fatList = new Array();
            rvinfoTable.bootstrapTable({
                //关闭通用查询
                url: 'customer/rvinfo/search/ids/' + ids,
                pk: 'rvi_id',
                search: false,
                toolbar: "",
                sortName: 'rv_date desc, rvi_id',
                sortOrder: 'desc',
                commonSearch: false,
                onLoadSuccess: function(data) {
                    fatList = data.fatList;
                    var fats = rvinfoTable.find('tbody tr .cls-fat');
                    var fatSelect = '<select class="form-control cls-fat-select hidden" style="width: 136px;"><option>--</option>';
                    for (var i in fatList) {
                        fatSelect += '<option value="' + i + '">' + fatList[i]['fat_name'] + '</option>';
                    }
                    fatSelect += '</select>';
                    for (var i = 0; i < data.rows.length; i++) {
                        // cls-fat
                        fats.eq(i).append(fatSelect);
                        fats.eq(i).find('select').val(data.rows[i]['fat_id']);
                    }
                },
                columns: [
                    [{
                            field: 'operate',
                            title: __('Operate'),
                            table: rvinfoTable,
                            events: {
                                'click .btn-editone': function(e, value, row, index) {
                                    var currentTr = $(this).parents('tr');
                                    currentTr.addClass('deepShow');
                                    currentTr.find('.btn-editone').addClass('hidden');
                                    currentTr.find('.btn-saveone').removeClass('hidden');
                                    currentTr.find('.cls-fat span').addClass('hidden');
                                    currentTr.find('.cls-fat select').removeClass('hidden');
                                    currentTr.find('.modifyContent').attr('contenteditable', true);
                                },
                                'click .btn-saveone': function(e, value, row, index) {
                                    var lIndex = layer.load(3);
                                    var currentTr = $(e.currentTarget).parents('tr');
                                    // var rviContent = $.trim(currentTr.find('textarea').val());
                                    var rviContent = $.trim(currentTr.find('.modifyContent').text());
                                    if (rviContent.length == 0) {
                                        layer.close(lIndex);
                                        layer.msg(__('请填写回访情况'), {
                                            icon: 2
                                        });
                                        return false;
                                    } else {
                                        if (rviContent.length >= 255) {
                                            layer.close(lIndex);
                                            layer.msg(__('请保证内容在255个字符以内'), {
                                                icon: 2
                                            });
                                            return false;
                                        }
                                    }
                                    $.post({
                                        url: '/customer/rvinfo/quickEdit',
                                        data: {
                                            ids: row.rvi_id,
                                            rvi_content: rviContent,
                                            fat_id: currentTr.find('.cls-fat-select').val()
                                        },
                                        dataType: 'json',
                                        async: false,
                                        success: function(res) {
                                            var icon = 2;
                                            if (res.code) {
                                                var updatedRvinfo = res.data.rvinfo;
                                                currentTr.removeClass('deepShow');
                                                if (res.data.canEdit) {
                                                    currentTr.find('.btn-editone').removeClass('hidden');
                                                    currentTr.find('.btn-saveone').addClass('hidden');
                                                } else {
                                                    currentTr.find('.btn-editone').remove();
                                                    currentTr.find('.btn-saveone').remove();
                                                }
                                                var updatedFat = fatList[updatedRvinfo.fat_id] ? fatList[updatedRvinfo.fat_id]['fat_name'] : '';
                                                currentTr.find('.cls-fat span').text(updatedFat);
                                                currentTr.find('.cls-fat-rvtime').html(Backend.api.formatter.datetime(updatedRvinfo.rv_time));
                                                currentTr.find('.cls-fat span').removeClass('hidden');
                                                currentTr.find('.cls-fat select').addClass('hidden');
                                                currentTr.find('.modifyContent').attr('title', updatedRvinfo.rvi_content);
                                                // currentTr.find('.modifyContent').css('border-width', 0);
                                                // currentTr.find('.modifyContent').prop('readonly', true);
                                                currentTr.find('.modifyContent').attr('contenteditable', false);
                                                icon = 1;
                                            }
                                            layer.msg(res.msg, {
                                                icon: icon
                                            });
                                        },
                                        error: function(e, xhr) {
                                            layer.msg(__('Operation failed'), {
                                                icon: 2
                                            });
                                        }
                                    })
                                    layer.close(lIndex);
                                }
                            },
                            formatter: function(value, row, index) {
                                if (row.canEdit) {
                                    return '<a href="javascript:;" class="btn btn-xs btn-default btn-editone" title="' + __('Edit') + '"><i class="fa fa-pencil"></i></a><a href="javascript:;" class="btn btn-xs btn-success btn-saveone hidden" title="' + __('Save') + '"><i class="fa fa-check"></i></a>';
                                }
                            },
                        }, {
                            field: 'rv_date',
                            title: __('Rv_date'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'rvt_type',
                            title: __('Rvt_type'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'rv_plan',
                            title: __('Rv_plan'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "60px",
                                        "word-wrap": "normal",
                                        'text-align': 'left !important',
                                    }
                                }
                            },
                        }, {
                            field: 'rvi_content',
                            title: __('Rvi_content'),
                            formatter: function(value, row, index) {
                                value = value ? value : '';
                                // var str = '<textarea class="tdModifyTextarea modifyContent" title="' + value + '" style="width:100%;resize:none;border-width:0" readonly>' + value + '</textarea>';
                                // return str;
                                var str = '<div contenteditable="false" class="modifyContent">' + value + '</div>';
                                return str;
                            },
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "width": "25%",
                                        "min-width": "180px",
                                        "word-wrap": "normal",
                                        'text-align': 'left !important',
                                    }
                                }
                            },
                            events: {
                                'click .btn-editTdContent': function(e, value, row, index) {
                                    $(this).parents('tr').find('.btn-edit').addClass('hidden');
                                    $(this).parents('tr').find('.tdModifyTextarea').removeClass('hidden');
                                    $(this).parents('tr').find('.btn-sureInfo').removeClass('hidden');
                                    $('.btn-sureInfo').click(function() {
                                        var modifyContent = $(this).parents('tr').find('.modifyContent').val();
                                        var modifyReason = $(this).parents('tr').find('.modifyReason').val();
                                        $(this).parents('tr').find('.btn-editTdContent').html(modifyContent);
                                        $(this).parents('tr').find('.btn-editTdReason').html(modifyReason);
                                        $(this).parents('tr').find('.btn-edit').removeClass('hidden');
                                        $(this).parents('tr').find('.tdModifyTextarea').addClass('hidden');
                                        $(this).parents('tr').find('.btn-sureInfo').addClass('hidden');
                                    })
                                }
                            },
                        }, 
                        // {
                        //     field: 'fat_name',
                        //     title: __('Rv_fat_id'),
                        //     class: 'cls-fat',
                        //     formatter: function(value, row, index) {
                        //         if (typeof value == 'undefined' || value == null) {
                        //             value = '';
                        //         }
                        //         var str = '<span style="color:#000;word-brea:break-all;text-align:left;width:100%" class="btn-editTdReason" title="点击修改">' + value + '</span>';
                        //         str += '<textarea class="tdModifyTextarea modifyReason hidden" value="' + value + '" style="width:145px;height:80px;resize:none">' + value + '</textarea>';
                        //         return str;
                        //     },
                        //     cellStyle: function(value, row, index) {
                        //         return {
                        //             css: {
                        //                 "white-space": "nowrap",
                        //             }
                        //         }
                        //     },
                        // }, 
                        {
                            field: 'rv_time',
                            title: __('rv_time'),
                            formatter: Table.api.formatter.datetime,
                            class: 'cls-fat-rvtime',
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, {
                            field: 'nickname',
                            title: __('Rv_admin_id'),
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        },{
                            field: 'createtime',
                            title: __('rvinfo_createtime'),
                            formatter: Table.api.formatter.datetime,
                            class: 'cls-fat-createtime',
                            cellStyle: function(value, row, index) {
                                return {
                                    css: {
                                        "white-space": "nowrap",
                                    }
                                }
                            }
                        }, 
                        // {
                        //     field: 'resolve_result',
                        //     title: __('Rv_resolve_result')
                        // }, {
                        //     field: 'resolve_admin_id',
                        //     title: __('Rv_resolve_admin_id')
                        // },
                    ]
                ]
            });
            Table.api.bindevent(rvinfoTable);
        },
        initDeductTable: function(tableSelector, Table, itemId) {
            yjyLayerOptions = Backend.getYjyLayerOptions();
            yjyLayerOptions.area = [$(top.window).width() <= 800 ? '95%' : '800px', $(window).height() <= 600 ? '95%' : '600px'];
            $(tableSelector + " tbody").empty();
            var lIndex = layer.load();
            $.post({
                data: {
                    ids: itemId
                },
                url: 'deduct/records/listforitem',
                dataType: 'html',
                success: function(data) {
                    layer.close(lIndex);
                    $(tableSelector).html(data);
                    $(tableSelector + ' .btn-viewone').each(function() {
                        $(this).on('click', function() {
                            yjyLayerOptions.content = $($(this).data('block-id')).html();
                            yjyLayerOptions.title = __('Staff benefit detail');
                            top.layer.open(yjyLayerOptions);
                            // op.Fast.api.open
                        })
                    });
                },
                error: function(e) {
                    layer.close(lIndex);
                    // layer.msg({
                    // __('error occurs')
                    // });
                }
            })
        },
        //初始化 选择弹窗----公用
        initComSelectPop: function(parentDoc, Table, tableSelector) {
            /*
                options = {
                    url: yjyComSelectParams.url,
                    pk: yjyComSelectParams.pk,
                    sortName: yjyComSelectParams.sortName,
                    search: yjyComSelectParams.search,
                    commonSearch: yjyComSelectParams.commonSearch,
                    //单选
                    singleParams: {fields: {}},
                    //跳转
                    redirectParams: {url: url, title: title, field: pkalias},
                    //多选
                    multiParams: {parentSelctor: parentSelctor, pkinputname: pkinputname}
                 }
             */
            //参数处理
            if (typeof yjyComSelectParams == 'undefined') {
                alert(__('Failed to initialize common select pop, params not found!'));
                return false;
            }
            options = {
                url: yjyComSelectParams.url,
                pk: yjyComSelectParams.pk,
                sortName: yjyComSelectParams.sortName,
                sortOrder: yjyComSelectParams.sortOrder ? yjyComSelectParams.sortOrder : 'DESC',
                search: yjyComSelectParams.search,
                commonSearch: yjyComSelectParams.commonSearch,
                columns: yjyComSelectParams.columns,
            };
            if (yjyComSelectParams.mode == 'redirect') {
                var params = yjyComSelectParams.redirectParams;
                options.redirect = {
                    url: params.url,
                    title: params.title,
                    field: params.field,
                };
            } else {
                if (yjyComSelectParams.mode == 'multi') {
                    var params = yjyComSelectParams.multiParams;
                    options.parentSelector = params.parentSelector;
                    options.pkinputname = params.pkinputname;
                    options.fieldsets = params.fields;
                } else {
                    //single
                    var params = yjyComSelectParams.singleParams;
                    options.fieldsets = params.fields;
                }
            }
            //模式处理
            if (yjyComSelectParams.mode == 'multi' || yjyComSelectParams.mode == 'cusmulti') {
                useCheckbox = true;
                //新增确定选择按钮
                // __('OK')
                $(tableSelector).after('<div class="text-center"><a class="btn btn-success" id="btn-multi-select"><i class="fa fa-plus"></i>' + ' 确认选择' + '</a></div>');
            } else {
                useCheckbox = false;
                if (yjyComSelectParams.mode == 'redirect') {
                    var operate = {};
                    operate.formatter = function(value, row) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-linkone" title=""><i class="fa fa-link"></i></a>';
                    };
                    //{paramName}占位符 + row[options.pk];
                    paramName = options.pk;
                    if (typeof(options.redirect.field) != 'undefined' && options.redirect.field != '') {
                        paramName = options.redirect.field;
                    }
                    var baseRedirectUrl = options.redirect.url + (options.redirect.url.match(/(\?|&)+/) ? ("&" + paramName + "=") : ("/" + paramName + "/"));
                    operate.events = {
                        //redirect
                        'click .btn-linkone': function(e, value, row, index) {
                            e.stopPropagation();
                            var redirectUrl = baseRedirectUrl + row[options.pk];
                            //关闭当前窗口，在父窗口打开新窗口(如在本窗口打开子弹窗，关闭本弹窗时会导致子弹窗被同时关闭)
                            parentDoc.layer.close(parentDoc.layer.getFrameIndex(window.name));
                            parentDoc.window.Fast.api.open(redirectUrl, options.redirect.title);
                        },
                    };
                } else {
                    var operate = {};
                    operate.formatter = function(value, row) {
                        return '<a href="javascript:;" class="btn btn-xs btn-success btn-selectone" title=""><i class="fa fa-check"></i></a>';
                    };
                    operate.events = {
                        //redirect
                        'click .btn-selectone': function(e, value, row, index) {
                            e.stopPropagation();
                            parentDoc.layer.close(parentDoc.layer.getFrameIndex(window.name));
                            //对父窗口的相关元素进行赋值
                            for (var field in options.fieldsets) {
                                if (parentDoc.$(options.fieldsets[field]).length > 0) {
                                    parentDoc.$(options.fieldsets[field]).val(row[field]);
                                    parentDoc.$(options.fieldsets[field]).trigger('change');
                                }
                            }
                        },
                    };
                }
            }
            //表格显示--列处理
            columns = options.columns;
            if (useCheckbox) {
                columns.unshift({
                    checkbox: true
                });
            }
            if (typeof(operate) != "undefined") {
                columns.push({
                    field: 'operate',
                    title: __('Operate'),
                    events: operate.events,
                    formatter: operate.formatter
                });
            }
            //bootstraptable
            Table.api.init();
            var table = $(tableSelector);
            table.bootstrapTable({
                // columns
                url: options.url,
                pk: options.pk,
                sortName: options.sortName,
                search: options.search,
                commonSearch: options.commonSearch,
                columns: columns,
                onLoadSuccess: function(data) {
                    //提示工具
                    $("[data-toggle='tooltip']").tooltip();
                },
            })
            //多选后确定按钮处理
            if (yjyComSelectParams.mode == 'multi') {
                $('#btn-multi-select').on('click', function() {
                    if (yjyComSelectParams.callback && parentDoc.window[yjyComSelectParams.callback]) {
                        $.map(table.bootstrapTable('getSelections'), function(row) {
                            func = parentDoc.window[yjyComSelectParams.callback];
                            func.call(func, row);
                        });
                    } else {
                        $.map(table.bootstrapTable('getSelections'), function(row) {
                            var rowHtml = '<tr><input type="hidden" name="' + options.pkinputname + '" value="' + row[options.pk] + '" />';
                            for (var i in options.fieldsets) {
                                rowHtml += '<td>' + (typeof(row[options.fieldsets[i]]) == "undefined" ? '' : row[options.fieldsets[i]]) + '</td>';
                            }
                            rowHtml += '<td><a href="javascript:;" class="btn btn-xs btn-danger btn-delone" title="" onclick="$(this).parents(\'tr\').remove();"><i class="fa fa-trash"></i></a></td>';
                            rowHtml += "</tr>";
                            parentDoc.$(options.parentSelector).append(rowHtml);
                        });
                    }
                    
                    parentDoc.layer.close(parentDoc.layer.getFrameIndex(window.name));
                })
            } else {
                $('#btn-multi-select').on('click', function() {
                    $.map(table.bootstrapTable('getSelections'), function(row) {
                        parentDoc.window.comselcallback(row);
                    });
                    parentDoc.layer.close(parentDoc.layer.getFrameIndex(window.name));
                });
            }
            return table;
        },
        initCustomerImgUpload: function() {
            if ($('#plupload_customer_img').length) {
                require(['plupload', 'toastr', 'layer'], function(plupload, Toastr, layer) {
                    layer.ready(function() {
                        layer.photos({
                            photos: '#customer-img-list',
                            //shift: 5 //0-6的选择，指定弹出图片动画类型，默认随机
                        });
                    });
                    //最后给"开始上传"按钮注册事件
                    var needAddPhotoListern = $('#customer-img-list .list-group-item').length == 0 ? true : false;
                    var button = $('#plupload_customer_btn');
                    var uploader = new plupload.Uploader({
                        browse_button: 'plupload_customer_img',
                        url: '/ajax/uploadCustomerImg/customerId/' + $('#plupload_customer_img').data('cus-id'),
                        filters: {
                            mime_types: [{
                                title: "图片文件",
                                extensions: "jpg,gif,png,bmp"
                            }],
                            max_file_size: '10mb',
                            prevent_duplicates: true
                        },
                        init: {
                            FilesAdded: function(up, files) {
                                var newValue = $('#plupload_customer_img').val() == '' ? files[0].name : $('#plupload_customer_img').val() + ',' + files[0].name;
                                $('#plupload_customer_img').val(newValue);
                            },
                            FileUploaded: function(up, file, info) {
                                //还原按钮文字及状态
                                $(button).prop("disabled", false).html($(button).data("bakup-html"));
                                $('#plupload_customer_img').prop("disabled", false).val('');
                                try {
                                    var ret = typeof info.response === 'object' ? info.response : JSON.parse(info.response);
                                    if (!ret.hasOwnProperty('code')) {
                                        $.extend(ret, {
                                            code: -2,
                                            msg: info.response,
                                            data: null
                                        });
                                    }
                                } catch (e) {
                                    var ret = {
                                        code: -1,
                                        msg: e.message,
                                        data: null
                                    };
                                }
                                if (ret.code === 1) {
                                    Toastr.success('上传成功！');
                                    $('#customer-img-list').append('<li class="list-group-item">' + '<img src="' + ret.data.url + '" title="' + ret.data.label + '" class="img-responsive" id="customer-img-' + ret.data.id + '">' + '</li>');
                                    if (needAddPhotoListern) {
                                        layer.ready(function() {
                                            layer.photos({
                                                photos: '#customer-img-list',
                                                //shift: 5 //0-6的选择，指定弹出图片动画类型，默认随机
                                            });
                                        });
                                        needAddPhotoListern = false;
                                    }
                                } else {
                                    Toastr.error(ret.msg);
                                }
                            },
                            UploadProgress: function(up, file) {
                                //这里可以改成其它的表现形式
                                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                                button.prop("disabled", true).html("<i class='fa fa-upload'></i> " + __('Upload') + file.percent + "%");
                                $('#plupload_customer_img').prop("disabled", true);
                            },
                            Error: function(up, err) {
                                Toastr.error('意外错误' + '(' + err.code + ')' + ': ' + err.message);
                            }
                        }
                    });
                    uploader.init();
                    // uploader.bind('FilesAdded', function(uploader, files) {});
                    // uploader.bind('UploadProgress', function(uploader, file) {});
                    $('#plupload_customer_btn').on('click', function() {
                        uploader.setOption("multipart_params", {
                            label: $('#plupload_customer_label').val(),
                            weigh: $('#plupload_customer_weigh').val()
                        });
                        uploader.start();
                    });
                });
            }
        },
        init: function() {
            //公共代码
            //添加ios-fix兼容iOS下的iframe
            if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
                $("html").addClass("ios-fix");
            }
            //配置Toastr的参数
            Toastr.options.positionClass = Config.controllername === 'index' ? "toast-top-right-index" : "toast-top-right";
            //点击包含.btn-dialog的元素时弹出dialog
            $(document).on('click', '.btn-dialog,.dialogit', function(e) {
                e.preventDefault();
                var options = $(this).data();
                options = options ? options : {};
                Backend.api.open(Backend.api.fixurl($(this).attr('href')), $(this).attr('title'), options);
            });
            //点击包含.btn-addtabs的元素时事件
            $(document).on('click', '.btn-addtabs,.addtabsit', function(e) {
                e.preventDefault();
                Backend.api.addtabs($(this).attr("href"), $(this).attr("title"));
            });
            //点击包含.btn-ajax的元素时事件
            $(document).on('click', '.btn-ajax,.ajaxit', function(e) {
                e.preventDefault();
                var options = $(this).data();
                if (typeof options.url === 'undefined' && $(this).attr("href")) {
                    options.url = $(this).attr("href");
                }
                Backend.api.ajax(options);
            });
            //修复含有fixed-footer类的body边距
            if ($(".fixed-footer").size() > 0) {
                $(document.body).css("padding-bottom", $(".fixed-footer").height());
            }
        },
        getYjyLayerOptions: function() {
            var yjyArea = [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 600 ? '600px' : '95%'];
            var yjyLayerOptions = {
                area: yjyArea,
                // Staff benefit detail
                title: __('None'),
                shadeClose: true,
                shade: false,
                maxmin: true,
                moveOut: true,
                // content: url,
                zIndex: Layer.zIndex,
            };
            return yjyLayerOptions;
        },
    };

    function subString(str, len, hasDot) {
        var newLength = 0;
        var newStr = "";
        var chineseRegex = /[^\x00-\xff]/g;
        var singleChar = "";
        var strLength = str.replace(chineseRegex, "**").length;
        for (var i = 0; i < strLength; i++) {
            singleChar = str.charAt(i).toString();
            if (singleChar.match(chineseRegex) != null) {
                newLength += 2;
            } else {
                newLength++;
            }
            if (newLength > len) {
                break;
            }
            newStr += singleChar;
        }
        if (hasDot && strLength > len) {
            newStr += "...";
        }
        return newStr;
    }
    Backend.api = $.extend(Fast.api, Backend.api);
    //将Moment渲染至全局,以便于在子框架中调用
    window.Moment = Moment;
    //将Backend渲染至全局,以便于在子框架中调用
    window.Backend = Backend;
    Backend.init();
    return Backend;
});