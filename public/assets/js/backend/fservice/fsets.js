define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'summernote'], function($, undefined, Backend, Table, Form, summernote) {
    window.yjyParams = {
        fprosets: {
            selectedProUl: '',
            selectedSetUl: '',
        },
        yjyTabCCls: 'y-p-tab-content',
        yjyTabPrefix: 'y-p-tab-',
        // let yjyVideoPrefix = 'y-p-video-';
        yjyTabCurId: 0,
        yjyTabCItemId: 0,
        yjyVideoCurId: 0,
        // list-tabs
        yjyTabList: {},
        usedProIds: {},
        usedSetIds: {},
    }
    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fservice/fsets/index',
                    add_url: 'fservice/fsets/add',
                    edit_url: 'fservice/fsets/edit',
                    del_url: 'fservice/fsets/del',
                    multi_url: 'fservice/fsets/multi',
                    table: 'fpro_sets',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                queryParams: function (params) {
                    console.log(params);
                    return params;
                },
                columns: [
                    [{
                        checkbox: true
                    }, {
                        field: 'id',
                        title: __('Id'),
                        // sortable: true,
                    }, {
                        field: 'dept_id',
                        title: __('Dept_id'),
                        formatter: function(value) {
                            return typeof yjyFServiceParams['deductDeptList'][value] != 'undefined' ? yjyFServiceParams['deductDeptList'][value] : '';
                        },
                        searchList: yjyFServiceParams['deductDeptList'],
                        sortable: true,
                    }, {
                        field: 'name',
                        title: __('Name'),
                        operate: 'LIKE %...%',
                    }, {
                        field: 'is_recommend',
                        title: __('Is_recommend'),
                        formatter: function(value) {
                            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '';
                        },
                        searchList: {
                            1: '是',
                            0: '否'
                        },
                    }, {
                        field: 'is_suit',
                        title: __('Is_suit'),
                        formatter: function(value) {
                            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '';
                        },
                        searchList: {
                            1: '是',
                            0: '否'
                        },
                    }, {
                        field: 'is_new',
                        title: __('Is_new'),
                        formatter: function(value) {
                            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '';
                        },
                        searchList: {
                            1: '是',
                            0: '否'
                        },
                    }, {
                        field: 'pic',
                        title: __('Pic'),
                        formatter: function(value) {
                            return value ? '<img width="100px" height="100px" src="' + value + '" />' : '';
                        },
                        searchable: false,
                    }, {
                        field: 'video',
                        title: __('Video'),
                        searchable: false,
                    }, {
                        field: 'sort',
                        title: __('Sort'),
                        searchable: false,
                        // sortable: true,
                    }, {
                        field: 'template_id',
                        title: __('Template_id'),
                        formatter: function(value) {
                            return typeof yjyFServiceParams['templateList'][value] != 'undefined' ? yjyFServiceParams['templateList'][value] : '';
                        },
                        searchList: yjyFServiceParams['templateList'],
                    }, {
                        field: 'status',
                        title: __('Status'),
                        formatter: function(value) {
                            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '';
                        },
                        searchList: {
                            1: '是',
                            0: '否'
                        },
                    }, {
                        field: 'operate',
                        title: __('Operate'),
                        table: table,
                        events: {
                            'click .btn-editone': function(e, value, row, index) {
                                e.stopPropagation();
                                var options = $(this).closest('table').bootstrapTable('getOptions');
                                let lIndex = Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'), {
                                    area: ['100%', '100%'],
                                });
                            },
                            'click .btn-delone': Table.api.events.operate,
                        },
                        // Table.api.events.operate,
                        formatter: Table.api.formatter.operate
                    }]
                ]
            });
            $('.btn-show-project').on('click', function() {
                Backend.api.addtabs('/base/project');
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            var parenttable = table.closest('.bootstrap-table');
            var options = table.bootstrapTable('getOptions');
            //Bootstrap操作区
            var toolbar = $(options.toolbar, parenttable);
            $(toolbar).off('click', Table.config.addbtn).on('click', Table.config.addbtn, function() {
                var ids = Table.api.selectedids(table);
                Fast.api.open(options.extend.add_url + (ids.length > 0 ? (options.extend.add_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ids.join(",") : ''), __('Add'), {
                    area: ['100%', '100%'],
                });
            });
            $('.btn-show-deptment').on('click', function() {
                Backend.api.addtabs('/base/deptment');
            });
        },
        add: function() {
            //初始化状态切换开关
            Backend.api.initYjySwitcher();
            //添加标签按钮 事件绑定
            Controller.api.bindNewTabEvent('#btn-add-tab');
            //编辑标签按钮 事件绑定
            Controller.api.bindEditTabEvent('#btn-edit-tab');
            //删除标签按钮 事件绑定
            Controller.api.bindRemoveTabEvent('#btn-del-tab');
            Controller.api.bindevent();
        },
        edit: function() {
            //初始化状态切换开关
            Backend.api.initYjySwitcher();
            //添加标签按钮 事件绑定
            Controller.api.bindNewTabEvent('#btn-add-tab');
            //编辑标签按钮 事件绑定
            Controller.api.bindEditTabEvent('#btn-edit-tab');
            //删除标签按钮 事件绑定
            Controller.api.bindRemoveTabEvent('#btn-del-tab');
            //渲染旧数据
            for (let tabId in yProSetInitParams.settings.tabs) {
                let tabName = yProSetInitParams.settings.tabs[tabId];
                if (typeof yProSetInitParams.settings.tabContents[tabId] != 'undefined') {
                    let contentParams = yProSetInitParams.settings.tabContents[tabId];
                    let curNewTabNo = tabId.replace(/[\D]+/g, '');
                    if (curNewTabNo > window.yjyParams.yjyTabCurId) {
                        window.yjyParams.yjyTabCurId = curNewTabNo;
                    };
                    Controller.api.yRenderNewTab(curNewTabNo, tabName, contentParams);
                }
            }
            Controller.api.bindevent();
        },
        comselectpop: function() {
            var table = Backend.initComSelectPop(parent, Table, '#table');
            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            // 搜索表单提交
            $("form.form-commonsearch").off('submit').on("submit", function(event) {
                event.preventDefault();
                return Backend.api.yjyCommonSearch("form.form-commonsearch", table, {
                    'fprosets.dept_id': '=',
                    'fprosets.name': 'LIKE %...%',
                });
            });
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
                if ($('#s-btn-submit').length > 0) {
                    $('#s-btn-submit').on('click', function() {
                        
                        if ($('[name="row[is_suit]"]').val() == 1) {
                            var proTotals = Controller.api.calProTotal();
                            $('[name="row[price]"]').val(proTotals['oriTotal']);
                            $('[name="row[set_price]"]').val(proTotals['proTotal']);
                        } else {
                            $('[name="row[price]"]').val(0);
                            $('[name="row[set_price]"]').val(0);
                        }
                        
                        $('[name="row[settings]"]').val(Controller.api.generateSettings());
                        $("form[role=form]").trigger('submit');
                    });
                }
                $('#btn-preview').on('click', function() {
                    let curDomain = location.href;
                    if (matches = curDomain.match(/http[s]?[\:][\/][\/][^/]+/)) {
                        curDomain = matches[0];
                    }
                    let settings = Controller.api.generateSettings();
                    let formDatas = ($('[role=form]').serializeArray());
                    var newWin = window.open();
                    formStr = '';
                    for (let formEle of formDatas) {
                        let formEleName = formEle.name;
                        let formEleValue = formEle.value;
                        if (formEle.name != 'row[settings]') {
                            formStr += `<input type="hidden" name='${formEleName}' value='${formEleValue}' />`;
                        }
                    }
                    //设置样式为隐藏，打开新标签再跳转页面前，如果有可现实的表单选项，用户会看到表单内容数据
                    formStr = `<form style="visibility:hidden;" method="POST" action="${curDomain}/web/set/preview">
                                 <input type="hidden" name="row[settings]" value='${settings}' />
                                 ${formStr}
                              </form>`;
                    newWin.document.body.innerHTML = formStr;
                    newWin.document.forms[0].submit();
                    return newWin;
                    // window.open('/web/index', 'newwindow', 'height=100, width=400, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no')
                });

                $('#btn-view-set-discount').on('click', function(e) {
                    var proTotals = Controller.api.calProTotal();
                    layer.msg(`原总价 ${proTotals['oriTotal']}，现总价 ${proTotals['proTotal']}，折扣率 ${proTotals['discountPercent']}%`, {icon: 1});
                });
                $('#btn-set-discount').on('click', function(e) {
                    $(this).attr('disabled', true);
                    var mode = $('#set-discout-mode').val();
                    var discountVal = parseFloat($('#set-discout-value').val());
                    if (isNaN(discountVal)) {
                        $(this).attr('disabled', false);
                        layer.msg('折扣设置错误，请重新设置', {icon: 2});
                            return false;
                    }

                    if (mode == 'percent') {
                        if (discountVal < 0 || discountVal > 100) {
                            $(this).attr('disabled', false);
                            layer.msg('折扣设置错误，请重新设置', {icon: 2});
                            return false;
                        } else {
                            var lIndex = layer.load(4);
                            $('.ul-pro-list .pro-price-amount').each(function() {
                                try {
                                    $(this).val(parseFloat($(this).data('pro-amount') * discountVal / 100).toFixed(2));
                                } catch(e) {
                                    layer.msg('设置失败', {icon: 2});
                                }
                            });
                            layer.close(lIndex);
                            $(this).attr('disabled', false);
                            var proTotals = Controller.api.calProTotal();
                            layer.msg(`折扣设置成功(原价： ${proTotals['oriTotal']} / 现价： ${proTotals['proTotal']} )`, {icon: 1});
                        }
                    } else {
                        if (discountVal < 0) {
                            $(this).attr('disabled', false);
                            layer.msg('折扣设置错误，请重新设置', {icon: 2});
                            return false;
                        } else {
                            var lIndex = layer.load(4);
                            var discountPercent = 1.0000;
                            var oriTotal = 0.00;
                            var proCnt = 0;
                            var newRealTotal = 0.00;

                            $('.ul-pro-list .list-group-item').each(function() {
                                var curProAmount = parseFloat($(this).find('.pro-price-amount').data('pro-amount')).toFixed(2);
                                var curProQty = parseInt($(this).find('.pro-set-qty').val());
                                if (isNaN(curProAmount) || isNaN(curProQty)) {
                                    layer.close(lIndex);
                                    $(this).attr('disabled', false);
                                    layer.msg('部分项目设置有误，请检查', {icon: 2});
                                    return false;
                                }
                                oriTotal += curProAmount * curProQty;
                                proCnt ++;
                            });

                            //oriTotal 原总价大于0 且 不等于新总价时 开始计算
                            //超出100%
                            if (oriTotal <= 0 || oriTotal < discountVal) {
                                layer.close(lIndex);
                                $(this).attr('disabled', false);
                                layer.msg('折后总价超出未打折前总价! 折前总价为： ' + oriTotal, {icon: 2});
                                return false;
                            }
                            
                            //中间计算百分比 精确到 万分位[10.0001%]
                            if (oriTotal > 0 && oriTotal >= discountVal) {
                                discountPercent = (1.0000 * discountVal / oriTotal).toFixed(6);
                                var successFlg = true;
                                var curProIndex = 0;
                                $('.ul-pro-list .list-group-item').each(function() {
                                    curProIndex ++;
                                    try {
                                        var curProAmount = parseFloat($(this).find('.pro-price-amount').data('pro-amount')).toFixed(2);
                                        var curProQty = parseInt($(this).find('.pro-set-qty').val());

                                        //通过最后一项 减少 预设与实际的总价差距
                                        if (proCnt == curProIndex) {
                                            var lastPercent = (discountVal - newRealTotal) > 0 ? 1.000000 * (discountVal - newRealTotal) / (curProAmount * curProQty) : 0;
                                        } else {
                                            lastPercent = discountPercent;
                                        }
                                        lastPercent = discountPercent;

                                        var curNewProAmount = (curProAmount * lastPercent).toFixed(2);
                                        var curProTotal = curNewProAmount * curProQty;
                                        newRealTotal += curProTotal;
                                        $(this).find('.pro-price-amount').val(curNewProAmount);
                                    } catch(e) {
                                        successFlg = false;
                                        return false;
                                    }
                                });
                                
                                layer.close(lIndex);
                                $(this).attr('disabled', false);
                                if (successFlg) {
                                    if (newRealTotal == discountVal) {
                                        layer.msg('折扣设置成功, 基准折扣率 ' + (parseFloat(discountPercent) * 100).toFixed(4), {icon: 1});
                                    } else {
                                        layer.msg('折扣设置成功, 基准折扣率 ' + (parseFloat(discountPercent) * 100).toFixed(4) + '%, 实际值与预设值 相差 ' + (parseFloat(discountVal) - parseFloat(newRealTotal)).toFixed(2) + `(预设： ${discountVal.toFixed(2)} / 实际： ${newRealTotal.toFixed(2)})，请自行调整单项价格`, {icon: 1});
                                    }
                                } else {
                                    layer.msg('设置失败', {icon: 2});
                                }
                            }
                        }
                    }

                    return false;
                });
            },
            calProTotal: function() {
                //原总价，现总价
                var oriTotal = 0.00;
                var proTotal = 0.00;
                $('.ul-pro-list .list-group-item').each(function() {
                    try {
                        var curProOriAmount = parseFloat($(this).find('.pro-price-amount').data('pro-amount')).toFixed(2);
                        var curProAmount = parseFloat($(this).find('.pro-price-amount').val()).toFixed(2);
                        var curProQty = parseInt($(this).find('.pro-set-qty').val());

                        oriTotal += curProOriAmount * curProQty;
                        proTotal += curProAmount * curProQty;
                    } catch(e) {
                        layer.msg('设置失败', {icon: 2});
                    }
                });
                return {oriTotal: oriTotal.toFixed(2), proTotal: proTotal.toFixed(2), discountPercent: oriTotal > 0 ? (100.00 * proTotal / oriTotal).toFixed(4) : '100.0000'};
            },
            generateSettings: function() {
                let tabContentParams = {};
                $(`.${window.yjyParams.yjyTabCCls}`).each(function() {
                    //tab content item loop
                    let tabId = $(this).attr('id');
                    let tmpCItemParams = [];
                    $(this).find('.tab-content-item').each(function() {
                        let row = {};
                        row['banner'] = $(this).find('.tab-content-item-banner').val();
                        // row['title'] = $(this).find('.tab-content-item-title').val();
                        row['desc'] = $(this).find('.tab-content-item-desc').val();
                        //project set loop
                        row['proSets'] = {};
                        $(this).find('.ul-pro-list .list-group-item').each(function() {
                            let selectedProId = $(this).data('proId');
                            let rank = $(this).find('.pro-set-rank').val();
                            if (rank < 0) {
                                rank = 0;
                            } else {
                                if (rank > 5) {
                                    rank = 5;
                                }
                            }
                            row['proSets']['p_' + selectedProId] = {
                                'pro_id': selectedProId,
                                'price': $(this).find('.pro-price-amount').val(),
                                'qty': $(this).find('.pro-set-qty').val(),
                                'rank': rank,
                            };
                        });
                        row['sets'] = {};
                        $(this).find('.ul-set-list .list-group-item').each(function() {
                            let selectedSetId = $(this).data('setId');
                            row['sets']['s_' + selectedSetId] = selectedSetId;
                        });
                        tmpCItemParams.push(row);
                    })
                    tabContentParams[tabId] = tmpCItemParams;
                })
                return JSON.stringify({
                    tabs: window.yjyParams.yjyTabList,
                    usedProIds: Object.getOwnPropertyNames(window.yjyParams.usedProIds),
                    usedSetIds: Object.getOwnPropertyNames(window.yjyParams.usedSetIds),
                    tabContents: tabContentParams
                });
            },
            bindNewTabEvent: function(newTabBtn) {
                //添加TAB
                $(newTabBtn).on('click', function() {
                    let newTabName = $('#name-new-tab').val();
                    if ($.trim(newTabName) == '') {
                        layer.msg('请输入TAB名', {
                            icon: 2
                        })
                        return false;
                    }
                    // window.yjyParams.yjyTabCurId = window.yjyParams.yjyTabCurId + 1;
                    let yjyTabCurId = ++window.yjyParams.yjyTabCurId;
                    //暂时只设一个，以后可能修改
                    yjyVideoCurId = yjyTabCurId;
                    Controller.api.yRenderNewTab(yjyTabCurId, newTabName, contentParams = {});
                });
            },
            bindEditTabEvent: function(editTabBtn) {
                //编辑TAB
                $(editTabBtn).on('click', function() {
                    let selectedTabId = $('#list-tabs').val();
                    let selectedTabName = $("#list-tabs option:selected").text();
                    let newTabName = $('#name-edit-tab').val();
                    layer.confirm(`确定更新 TAB： '${selectedTabName}' => '${newTabName}' ？`, {}, function(index, layero) {
                        try {
                            //更新TAB, 更新LIST, 更新SELECT
                            $(`[href="#${selectedTabId}"]`).text(newTabName);
                            window.yjyParams.yjyTabList[selectedTabId] = newTabName;
                            $('#list-tabs [value=' + selectedTabId + ']').text(newTabName);
                            layer.close(index);
                            $(`[href="#${selectedTabId}"]`).trigger('click');
                            layer.msg('修改成功');
                        } catch (e) {
                            layer.close(index);
                            layer.msg('修改失败', {
                                icon: 2
                            });
                        }
                    });
                });
            },
            bindRemoveTabEvent: function(removeTabBtn) {
                //删除TAB
                // '#btn-del-tab'
                $(removeTabBtn).on('click', function() {
                    let selectedTabId = $('#list-tabs').val();
                    let selectedTabName = $("#list-tabs option:selected").text();
                    if (selectedTabId) {
                        layer.confirm(`确定删除 TAB[ ${selectedTabName} ]？`, {}, function(index, layero) {
                            // 清除TAB, 清除TAB CONTENT, 清除 LIST, SELECT 更新
                            try {
                                //tab 清除
                                $('#y-p-tab-div [data-id=' + selectedTabId + ']').remove();
                                //tab content 清除 -- 先根据 ITEM 清除 产品列表
                                $('#' + selectedTabId) && $('#' + selectedTabId).find('.tab-content-item').each(function() {
                                    Controller.api.yRemoveTabContentItem('#' + $(this).attr('id'), 0);
                                });
                                $('#' + selectedTabId).remove();
                                //select 数据源列表更新
                                delete window.yjyParams.yjyTabList[selectedTabId];
                                //select 更新
                                $('#list-tabs [value=' + selectedTabId + ']').remove();
                                layer.close(index);
                                if ($('#y-p-tab-div li.active').length == 0 && Object.getOwnPropertyNames(window.yjyParams.yjyTabList).length > 0) {
                                    let firstTabId = Object.getOwnPropertyNames(window.yjyParams.yjyTabList)[0];
                                    $(`[href="#${firstTabId}"]`).trigger('click');
                                }
                                layer.msg('删除成功');
                            } catch (e) {
                                layer.close(index);
                                layer.msg('删除失败', {
                                    icon: 2
                                });
                            }
                        }, function(index) {
                            layer.close(index);
                        });
                    }
                });
            },
            yRenderNewTab: function(newTabId, newTabName, contentParams = []) {
                // let yjyTabCurId = window.yjyParams.yjyTabCurId;
                //暂时只设一个，以后可能修改
                // yjyVideoCurId = yjyTabCurId;
                let newTabNo = newTabId;
                if (parseInt(newTabId) > 0) {
                    newTabId = `${window.yjyParams.yjyTabPrefix}${newTabNo}`;
                } else {
                    newTabNo = newTabId.replace(/[\D]+/g, '');
                }
                let newTabHtml = `
                <li data-id="${newTabId}">
                    <a data-toggle="tab" href="#${newTabId}">
                        ${newTabName}
                    </a>
                </li>

`;
                let newTabContent = `
                <div class="y-drag-container ${window.yjyParams.yjyTabCCls} tab-pane fade" id="${newTabId}" style="padding: 6px 0;">
                    <div class="y-c-item-toolbar"><a class="btn btn-success" id="y-btn-add-item-${newTabNo}" data-tab-id="${newTabNo}"><i class="fa fa-plus">添加组</i></a></div>
                </div>
`;
                let newTabItem = `
                <option value="${newTabId}">${newTabName}</option>
`;
                $('#y-p-tab-div').append(newTabHtml);
                $('#y-p-tab-content-div').append(newTabContent);
                if (contentParams && Object.getOwnPropertyNames(contentParams).length > 0) {
                    for (let contentItemIdx in contentParams) {
                        Controller.api.yAddTabContentItem(`#${newTabId}`, ++window.yjyParams.yjyTabCItemId, contentParams[contentItemIdx]);
                    }
                } else {
                    //在新 TAB CONTENT中 默认添加 一个ITEM
                    Controller.api.yAddTabContentItem(`#${newTabId}`, ++window.yjyParams.yjyTabCItemId);
                }
                //添加ITEM 按钮事件绑定
                let cBtnAddItemID = `#y-btn-add-item-${newTabNo}`;
                let cItemContainer = `#${newTabId}`;
                $(cBtnAddItemID).on('click', function() {
                    Controller.api.yAddTabContentItem(cItemContainer, ++window.yjyParams.yjyTabCItemId);
                });
                $('#list-tabs').append(newTabItem);
                window.yjyParams.yjyTabList[newTabId] = newTabName;
                //切换到新添加的TAB
                $(`[href="#${newTabId}"]`).trigger('click');
            },
            //新增TAB
            yAddTab: function() {},
            // 编辑TAB
            yEditTab: function() {},
            yRemoveTabContentItem: function(itemId, layerIndex) {
                // 清除 原产品表中相应数据
                $(itemId).find('.ul-pro-list li').each(function() {
                    let proId = $(this).data('proId');
                    //列表中存在并删除
                    window.yjyParams.usedProIds[proId] && delete window.yjyParams.usedProIds[proId];
                })
                $(itemId).find('.ul-set-list li').each(function() {
                    let setId = $(this).data('setId');
                    //列表中存在并删除
                    window.yjyParams.usedSetIds[setId] && delete window.yjyParams.usedSetIds[setId];
                })
                $(itemId).remove();
                layerIndex && layer.close(layerIndex);
            },
            //新增TAB内 子项
            yAddTabContentItem: function(targetContainer, itemId, itemParams = {
                // title: '',
                desc: '',
                banner: '',
                proSets: {}
            }) {
                let banner = typeof itemParams.banner != 'undefined' ? itemParams.banner : '';
                `<div class="form-group" style="overflow: hidden;">
                    <label for="c-desc" class="control-label col-xs-12 col-sm-4 col-md-2">标题:</label>
                    <div class="col-xs-12 col-sm-8 col-md-10">
                        <input type="text" class="form-control tab-content-item-title" value="${itemParams.title}" max-length="20" placeholder="请输入标题" />
                    </div>
                </div>`;
                let itemHtml = `
                    <div class="tab-content-item y-drag" id="tab-content-item-${itemId}">
                        <div class="form-group" style="overflow: hidden;">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                组： <span class="text-success" style="width: 30px;display: inline-block;"><i class="fa fa-star"></i>${itemId}</span>
                                <a class="btn btn-danger" id="btn-del-item-${itemId}"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="form-group" style="overflow: hidden;">
                            <label for="c-pic" class="control-label col-xs-12 col-sm-2">组banner:</label>
                            <div class="col-xs-12 col-sm-10">
                                <div class="form-inline">
                                    <input id="tab-content-item-banner-${itemId}" data-rule="required" class="form-control tab-content-item-banner" size="50" type="text" value="${banner}">
                                    <span><button type="button" id="item-plupload-pic-${itemId}" class="btn btn-danger plupload" data-input-id="tab-content-item-banner-${itemId}" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="item-p-pic-${itemId}"><i class="fa fa-upload"></i> 上传</button></span>
                                    <span><button type="button" id="item-fachoose-pic-${itemId}" class="btn btn-primary fachoose" data-input-id="tab-content-item-banner-${itemId}" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                    <ul class="row list-inline plupload-preview" id="item-p-pic-${itemId}"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="overflow: hidden;">
                            <label for="c-desc" class="control-label col-xs-12 col-sm-4 col-md-2">描述:</label>
                            <div class="col-xs-12 col-sm-8 col-md-10">
                                <textarea id="c-desc" class="form-control tab-content-item-desc yjy-enable-editor summernote" rows="3" cols="50" placeholder="请输入描述">${itemParams.desc}</textarea>
                            </div>
                        </div>
                        <div class="form-group" style="overflow: hidden;">
                            <label for="c-pic" class="control-label col-xs-12 col-sm-4 col-md-2">套餐:</label>
                            <div class="col-xs-12 col-sm-8 col-md-10">
                                <div class="form-group">
                                    <a class="btn btn-success" id="btn-add-set-${itemId}" data-target="#ul-set-list-${itemId}"><i class="fa fa-plus"></i></a>
                                </div>
                                <ul class="list-group ul-set-list" id="ul-set-list-${itemId}" style="overflow: hidden;">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group" style="overflow: hidden;">
                            <label for="c-pic" class="control-label col-xs-12 col-sm-4 col-md-2">项目:</label>
                            <div class="col-xs-12 col-sm-8 col-md-10">
                                <div class="form-group">
                                    <a class="btn btn-success" id="btn-add-pro-${itemId}" data-target="#ul-pro-list-${itemId}"><i class="fa fa-plus"></i></a>
                                </div>
                                <ul class="list-group ul-pro-list" id="ul-pro-list-${itemId}" style="overflow: hidden;">
                                </ul>
                            </div>
                        </div>
                    </div>
`;
                $(targetContainer).append(itemHtml);
                $(`#tab-content-item-${itemId} .yjy-enable-editor.summernote,#tab-content-item-${itemId} .yjy-enable-editor.editor`).summernote({
                    height: 180,
                    lang: 'zh-CN',
                    fontNames: ['Arial', 'Arial Black', 'Serif', 'Sans', 'Courier', 'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', "Open Sans", "Hiragino Sans GB", "Microsoft YaHei", '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆', ],
                    fontNamesIgnoreCheck: ["Open Sans", "Microsoft YaHei", '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆'],
                    toolbar: [
                        ['style', ['style', 'undo', 'redo']],
                        ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                        ['fontname', ['color', 'fontname', 'fontsize']],
                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                        ['table', ['table', 'hr']],
                        // ['insert', ['link', 'picture', 'video']],
                        // ['select', ['image', 'attachment']],
                        // ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                    buttons: {
                        // image: '',
                        // attachment: '',
                    },
                    dialogsInBody: true,
                    callbacks: {
                        onChange: function(contents) {
                            $(this).val(contents);
                            $(this).trigger('change');
                        },
                        onInit: function() {},
                        onImageUpload: function(files) {
                            var that = this;
                            //依次上传图片
                            for (var i = 0; i < files.length; i++) {
                                Upload.api.send(files[i], function(data) {
                                    var url = Fast.api.cdnurl(data.url);
                                    $(that).summernote("insertImage", url, 'filename');
                                });
                            }
                        }
                    }
                });
                //删除组按钮 事件绑定
                $(`#btn-del-item-${itemId}`).on('click', function() {
                    layer.confirm(`确定删除 组 ${itemId} 吗？`, {}, function(index, layero) {
                        return Controller.api.yRemoveTabContentItem(`#tab-content-item-${itemId}`, index);
                    })
                })
                //添加套餐按钮 事件绑定
                var editProSetId = $('#h-edit-pro-set-id').length > 0 ? $('#h-edit-pro-set-id').val() : 0;
                $(`#btn-add-set-${itemId}`).on('click', function() {
                    var params = '?mode=multi&is_set=1&id=' + editProSetId;
                    let targetUlSelector = $(this).data('target');
                    //设定当前 选中的 UL
                    window.yjyParams.fprosets.selectedSetUl = targetUlSelector;
                    Fast.api.open('fservice/fsets/comselectpop' + params, '选择套餐');
                });
                //添加项目按钮 事件绑定
                $(`#btn-add-pro-${itemId}`).on('click', function() {
                    var params = '?mode=multi';
                    let targetUlSelector = $(this).data('target');
                    //设定当前 选中的 UL
                    window.yjyParams.fprosets.selectedProUl = targetUlSelector;
                    Fast.api.open('fservice/pro/comselectpop' + params, '选择项目');
                });
                //如有套餐设置 添加套餐
                if (itemParams && itemParams.sets && (typeof yProSetInitParams != 'undefined') && (typeof yProSetInitParams.setList != 'undefined')) {
                    for (let setTag in itemParams.sets) {
                        let setId = itemParams.sets[setTag];

                        if (yProSetInitParams.setList[setId]) {
                            let row = yProSetInitParams.setList[setId];
                            Controller.api.yRenderSetLi(`#ul-set-list-${itemId}`, row);
                        }
                    }
                }
                //如有产品设置 添加产品
                if (itemParams && itemParams.proSets && (typeof yProSetInitParams != 'undefined') && (typeof yProSetInitParams.proList != 'undefined')) {
                    for (let proTag in itemParams.proSets) {
                        let proId = itemParams.proSets[proTag]['pro_id'];
                        let proSet = itemParams.proSets[proTag];
                        if (yProSetInitParams.proList[proId]) {
                            let row = yProSetInitParams.proList[proId];
                            row = Object.assign({}, row, proSet);
                            console.log(proSet);
                            console.log(row);
                            Controller.api.yRenderProLi(`#ul-pro-list-${itemId}`, row);
                        }
                    }
                }

                Controller.api.bindChangePosEvent(`#ul-set-list-${itemId}`);
                Controller.api.bindChangePosEvent(`#ul-pro-list-${itemId}`);
            },
            formatProLi: function(row, setPrice, setQty, setRank) {
                return `
                        <li class="list-group-item col-sm-6 col-md-4" data-pro-id="${row.pro_id}">
                            <a class="btn btn-sm btn-primary btn-pos-up" id="btn-up-pro-${row.pro_id}" data-pro-id="${row.pro_id}">
                                <i class="fa fa-arrow-up"></i>
                            </a>
                            <a class="btn btn-sm btn-primary btn-pos-down" id="btn-down-pro-${row.pro_id}" data-pro-id="${row.pro_id}">
                                <i class="fa fa-arrow-down"></i>
                            </a>
                            <a class="btn btn-sm btn-danger btn-del-pro" id="btn-del-pro-${row.pro_id}" data-pro-id="${row.pro_id}">
                                <i class="fa fa-times"></i>
                            </a>
                            <img src="${row.cover}" class="img img-responsive center-block" />
                            <div class="y-one-line">名称： ${row.pro_name}</div>
                            <div class="y-one-line">规格： ${row.pro_spec}</div>
                            <div>原价格： ${row.pro_amount}</div>
                            <div class="input-group">
                                <span class="input-group-addon">折扣价：</span>
                                <input type="number" class="form-control pro-price-amount" data-pro-id="${row.pro_id}" data-pro-amount="${row.pro_amount}" value="${setPrice}" placeholder="使用价格" />
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">套餐量：</span>
                                <input type="number" class="form-control pro-set-qty" data-pro-id="${row.pro_id}" value="${setQty}" placeholder="数量仅套餐有效" />
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">星级：</span>
                                <input type="number" class="form-control pro-set-rank" data-pro-id="${row.pro_id}" value="${setRank}" placeholder="推荐星级0~5"  min="0" max="5" />
                            </div>
                        </li>
                    `;
            },
            formatSetLi: function(row) {
                return `
                    <li class="list-group-item col-sm-6 col-md-4" data-set-id="${row.id}">
                         <a class="btn btn-sm btn-primary btn-pos-up" id="btn-up-pro-${row.pro_id}" data-pro-id="${row.pro_id}">
                                <i class="fa fa-arrow-up"></i>
                            </a>
                            <a class="btn btn-sm btn-primary btn-pos-down" id="btn-down-pro-${row.pro_id}" data-pro-id="${row.pro_id}">
                                <i class="fa fa-arrow-down"></i>
                            </a>
                        <a class="btn btn-sm btn-danger btn-del-set" id="btn-del-set-${row.id}" data-set-id="${row.id}">
                            <i class="fa fa-times"></i>
                        </a>
                        <img src="${row.pic}" class="img img-responsive center-block" />
                        <div class="y-one-line">名称： ${row.name}</div>
                    </li>
                `;
            },
            yRenderProLi: function(selectedProUl, row) {
                // let selectedProUl = window.yjyParams.fprosets.selectedProUl;
                if (typeof window.yjyParams.usedProIds[row.pro_id] != 'undefined') {
                    layer.msg('同一种产品不能重复出现', {
                        icon: 2
                    });
                    return false;
                } else {
                    window.yjyParams.usedProIds[row.pro_id] = row.pro_id;
                    let setPrice = typeof row.price != 'undefined' ? row.price : row.pro_amount;
                    let setQty = typeof row.qty != 'undefined' ? row.qty : 1;
                    let setRank = typeof row.rank != 'undefined' ? row.rank : 0;
                    $(selectedProUl).append(Controller.api.formatProLi(row, setPrice, setQty, setRank));

                    Controller.api.bindDelProEvent(`#btn-del-pro-${row.pro_id}`);
                }
            },
            yRenderSetLi: function(selectedSetUl, row) {
                if (typeof window.yjyParams.usedSetIds[row.id] != 'undefined') {
                    layer.msg('同一种套餐不能重复出现', {
                        icon: 2
                    });
                    return false;
                }
                //相同自动覆盖
                window.yjyParams.usedSetIds[row.id] = row.id;
                $(selectedSetUl).append(Controller.api.formatSetLi(row));
                Controller.api.bindDelSetEvent(`#btn-del-set-${row.id}`);
            },
            //删除项目
            bindDelProEvent: (btnDelPro = 'ALL') => {
                //'#btn-del-pro-${row.pro_id}'
                if (btnDelPro == 'ALL') {
                    btnDelPro = '.btn-del-pro';
                }
                $(btnDelPro).on('click', function() {
                    let proId = $(this).data('proId');
                    window.yjyParams.usedProIds[proId] && delete window.yjyParams.usedProIds[proId];
                    // parents`li[data-pro-id="${proId}"]`
                    $(this).parent('li').remove();
                });
            },
            //type pro or set
            bindChangePosEvent: function(proUlSelector) {
                var btnUpSelector = '.btn-pos-up';
                var btnDownSelector = '.btn-pos-down';
                // changeType
                $(proUlSelector).off('click', btnUpSelector).on('click', btnUpSelector, function() {
                    var preLi = $($(this).parent('li')).prev();
                    if (preLi.length == 0) {
                        layer.msg('已经在第一位了', {icon: 2});
                    } else {
                        var curLi = $(this).parent('li').clone(true);
                        $(this).parent('li').remove();
                        $(preLi).before(curLi);
                    }
                });
                $(proUlSelector).off('click', btnDownSelector).on('click', btnDownSelector, function() {
                    var preLi = $($(this).parent('li')).next();
                    if (preLi.length == 0) {
                        layer.msg('已经在最后一位了', {icon: 2});
                    } else {
                        var curLi = $(this).parent('li').clone(true);
                        $(this).parent('li').remove();
                        $(preLi).after(curLi);
                    }
                })
            },
            //删除套餐
            bindDelSetEvent: (btnDelSet = 'ALL') => {
                //'#btn-del-pro-${row.pro_id}'
                if (btnDelSet == 'ALL') {
                    btnDelSet = '.btn-set-pro';
                }
                $(btnDelSet).on('click', function() {
                    let setId = $(this).data('setId');
                    window.yjyParams.usedSetIds[setId] && delete window.yjyParams.usedSetIds[setId];
                    $(this).parent('li').remove();
                });
            },
        }
    };
    window.comselcallback = function(row) {
        let selectedProUl = window.yjyParams.fprosets.selectedProUl;
        Controller.api.yRenderProLi(selectedProUl, row);
    };
    window.setcomselcallback = function(row) {
        let selectedSetUl = window.yjyParams.fprosets.selectedSetUl;
        Controller.api.yRenderSetLi(selectedSetUl, row);
    }
    return Controller;
});