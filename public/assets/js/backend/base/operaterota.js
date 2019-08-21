define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'layer', 'selectpage'], function($, undefined, Backend, Table, Form, Layer, selectpage) {
    var Controller = {
        index: function() {
            Form.events.datetimepicker($("#check-orta-form"));
            var oldOrtDate = $('#ort_date').val();
            $('#ort_date').on('blur', function() {
                if ($('#ort_date').val() != oldOrtDate) {
                    Layer.load('3');
                    $('#check-orta-form').submit();
                }
            });
            $('.btn-refresh').on('click', function() {
                location.reload();
            });
            $('#btn-help').on('click', function() {
                var area = [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 340 ? '420px' : '95%'];
                var lIndex = layer.open({
                    type: 1,
                    title: false,
                    closeBtn: false,
                    area: area,
                    shade: 0.8,
                    id: 'LAY_rota_help',
                    btn: ['确定'],
                    btnAlign: 'c',
                    moveType: 1 //拖拽模式，0或者1
                        ,
                    content: $('#block-help').html(),
                    success: function(layero) {
                        layer.close(lIndex);
                    }
                });
            });
            var selectedArr = {};
            var selectedCnt = 0;
            //预约
            $('#btn-book').on('click', function() {
                if (selectedCnt <= 0 || (Object.keys(selectedArr)).length <= 0) {
                    layer.alert('请选择医生及时间', {
                        icon: 2
                    });
                    return false;
                }
                Fast.api.open('base/operaterota/book?targetDate=' + $('#h-targetDate').val() + '&periods=' + encodeURI(JSON.stringify(selectedArr)), '预约手术', {});
            })
            $('.staff_ul li').each(function(ele, index) {
                $(this).on('click', function() {
                    var status = $(this).data('status');
                    if (status == '0') {
                        layer.alert('休假中', {
                            icon: 2
                        });
                        return false;
                    }
                    if (status == '2') {
                        // layer.alert('已有预约', {
                        //     icon: 2
                        // });
                        Controller.api.toggleSelectedBookInfo($(this), 'li.rota_period', 'li.book-item', '#rota-table tbody tr');
                        return false;
                    }
                    $(this).toggleClass('selected');
                    var operatorId = $(this).data('operator-id');
                    var period = $(this).data('period');
                    if (status == 1) {
                        var newStatus = 3;
                        if (typeof selectedArr[operatorId] == 'undefined') {
                            selectedArr[operatorId] = {};
                        }
                        selectedArr[operatorId][period] = 1;
                        selectedCnt++;
                    } else {
                        var newStatus = 1;
                        delete selectedArr[operatorId][period];
                        selectedCnt--;
                    }
                    $(this).data('status', newStatus);
                });
            });
            $('#btn-new-rota').on('click', function() {
                Backend.api.addtabs('/base/operaterota/newschedule?ortDate=' + $('#h-targetDate').val(), '新的排班');
            });
            //取消预约
            $('.btn-cancel-book').each(function() {
                $(this).on('click', function() {
                    var obk_id = $(this).data('id');
                    console.log(obk_id);
                    var confirmLIndex = layer.confirm(__('Is Cancel?'), function(index, layero) {
                        $.ajax({
                            url: 'base/Operaterota/cancelbook',
                            data: {
                                obk_id: obk_id
                            },
                            dataType: 'json',
                            success: function(res) {
                                layer.close(confirmLIndex);
                                if (res.code == 1) {
                                    layer.msg(__('Operation completed'), {
                                        icon: 1
                                    });
                                    window.location.reload();
                                } else {
                                    layer.msg(res.msg, {
                                        icon: 2
                                    });
                                }
                            }
                        })
                    })
                });
            });
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        newschedule: function() {
            Form.events.datetimepicker($("#form-new"));
            var oldOrtDate = $('#ort_date').val();
            $('#ort_date').on('blur', function() {
                if ($('#ort_date').val() != oldOrtDate) {
                    Layer.load('3');
                    window.location.href = '/base/operaterota/newschedule?ortDate=' + $(this).val();
                }
            });
            $('.staff_ul li:not(.active)').each(function(ele, index) {
                $(this).on('click', function() {
                    $(this).toggleClass('disabled');
                    $(this).data('status', $(this).data('status') ? 0 : 1);
                });
            });
            $('.btn-assign-all').each(function(ele, index) {
                $(this).on('click', function() {
                    var lis = $($(this).data('target')).find('li:not(.active)');
                    for (var i = 0; i < lis.length; i++) {
                        lis.eq(i).removeClass('disabled');
                        lis.eq(i).data('status', 1);
                    }
                });
            });
            $('.btn-cancel-all').each(function(ele, index) {
                $(this).on('click', function() {
                    var lis = $($(this).data('target')).find('li:not(.active)');
                    for (var i = 0; i < lis.length; i++) {
                        lis.eq(i).addClass('disabled');
                        lis.eq(i).data('status', 0);
                    }
                });
            })
            $('#btn-view-rota').on('click', function() {
                Backend.api.addtabs('/base/operaterota?ort_date=' + $('#ort_date').val(), '手术值班管理');
            });
            $('#btn-rota').on('click', function() {
                $('#btn-rota').prop('disabled', true);
                var rotaData = new Array();
                $('.staff_ul').each(function() {
                    var rowData = {
                        staffId: $(this).data('operator-id'),
                        timePeriods: {}
                    };
                    var lis = $(this).find('li');
                    for (var i = 0; i < lis.length; i++) {
                        var currentLi = lis.eq(i);
                        //有预约的不予处理
                        if (currentLi.data('status') != 2 && currentLi.data('book-id') == 0) {
                            rowData['timePeriods'][currentLi.data('periord')] = {
                                ort_id: currentLi.data('ort-id'),
                                ort_status: currentLi.data('status'),
                            };
                        }
                    }
                    rotaData.push(rowData);
                })
                $.post({
                    url: 'base/operaterota/newschedule',
                    data: {
                        ortDate: $('#ort_date').val(),
                        rotaData: JSON.stringify(rotaData)
                    },
                    success: function(res) {
                        if (res.code) {
                            Layer.msg('排班成功，即将显示 手术值班 情况', {
                                icon: 1
                            }, function() {
                                Backend.api.addtabs('/base/operaterota?ort_date=' + $('#ort_date').val(), '手术值班管理');
                                Backend.api.closetabs(location.href);
                            })
                        } else {
                            Layer.msg('排班成功，即将显示 手术值班 情况', {
                                icon: 2
                            });
                        }
                    }
                })
            });
        },
        book: function() {
            Controller.api.bindSelCus();
            Controller.api.bindSelPro();
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
            toggleSelectedBookInfo: function(ele, rotaLiSelector, bookItemSelector, rotaTrSelector) {
                var bookId = $(ele).data('book-id');
                var extraSelector = '[data-book-id="' + bookId + '"]';
                if ($(rotaLiSelector + extraSelector).length == 0) {
                    return false;
                }
                var showStatus = $(rotaLiSelector + extraSelector).hasClass('focus-show');
                $(rotaLiSelector).parents('tr').removeClass('hidden');
                if (!showStatus) {
                    $(rotaTrSelector).addClass('hidden');
                    $(rotaLiSelector + extraSelector).parents('tr').removeClass('hidden');
                    $(bookItemSelector).addClass('hidden');
                    $(bookItemSelector + extraSelector).removeClass('hidden');
                } else {
                    $(rotaTrSelector).removeClass('hidden');
                    $(bookItemSelector).removeClass('hidden');
                }
                $(rotaLiSelector).removeClass('focus-show');
                if (!showStatus) {
                    $(rotaLiSelector + extraSelector).addClass('focus-show');
                }
            },
            restoreBookSelected: function(rotaLiSelector, bookItemSelector) {
                $(rotaLiSelector).tooltip('destroy')
                $(bookItemSelector).removeClass('hidden');
            },
            bindSelCus: function() {
                $('#selector-customer').selectPage({
                    data: '/customer/customer/comselectpop',
                    params: function() {
                        return {
                            "pkey_name": "ctm_id",
                            "order_by": [
                                ["ctm_id", "ASC"],
                            ],
                            "field": "ctm_name",
                        };
                    },
                    pageSize: 10,
                    showField: "ctm_name",
                    searchField: "ctm_id, ctm_name, ctm_mobile",
                    keyField: 'ctm_id',
                    andOr: "OR",
                    multiple: false,
                    pagination: true,
                    showField: "ctm_name",
                    eAjaxSuccess: function(data) {
                        return data;
                    },
                    formatItem: function(data) {
                        var mLength = data.ctm_mobile.length;
                        mobile = data.ctm_mobile
                        if (mLength > 4) {
                            var mask = new Array(mLength - 4 + 1).join('*');
                            var leftP = parseInt((mLength - 4) / 2);
                            var mobile = data.ctm_mobile.substr(0, leftP) + mask + data.ctm_mobile.substr(leftP + 4);
                        }
                        return '[' + data.ctm_id + '] ' + data.ctm_name + ' (' + mobile + ')';
                    },
                    eSelect: function(data) {
                        $('[name=customer_id]').val(data.ctm_id);
                    },
                });
                if ($('.sp_container')) {
                    var selectWidth = $('.sp_container').width();
                    $('.sp_result_area').css('width', selectWidth);
                }
            },
            bindSelPro: function(proType) {
                $('#selector-pro').selectPage({
                    data: '/base/project/comselectpop',
                    params: function() {
                        return {
                            "pkey_name": "pro_id",
                            "order_by": [
                                ["pro_id", "ASC"],
                            ],
                            "field": "pro_name",
                            "yjyCustom[pro_type]": proType,
                            "yjyCustom[pro_status]": 1,
                        };
                    },
                    pageSize: 10,
                    showField: "pro_name",
                    searchField: "pro_name,pro_spec,pro_spell",
                    keyField: 'pro_id',
                    andOr: "OR",
                    multiple: false,
                    pagination: true,
                    showField: "pro_name",
                    eAjaxSuccess: function(data) {
                        return data;
                    },
                    formatItem: function(data) {
                        return data.pro_amount + " | " + data.pro_name + " | " + data.pro_spec;
                    },
                    eSelect: function(data) {
                        // $('[name=pro_id]').val(data.pro_id);
                        if ($(`#t-book-pro tr[data-pro-id=${data.pro_id}]`).length == 0) {
                            $('#t-book-pro tbody').append(`
                                <tr data-pro-id="${data.pro_id}">
                                    <td style="width: 40%; word-wrap: break-all;">
                                        ${data.pro_name}
                                        <input type="hidden" name="pro_id[]" value="${data.pro_id}" />
                                    </td>
                                    <td style="width: 40%; word-wrap: break-all;">
                                        ${data.pro_spec}
                                    </td>
                                    <td width="20%">
                                        <a href="javascript:;" class="btn btn-danger btn-del-pro">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            `);
                        }
                    },
                });
                $('#t-book-pro').on('click', '.btn-del-pro', function() {
                    console.log(this);
                    $(this).parents('tr').remove();
                });
                if ($('.sp_container')) {
                    var selectWidth = $('.sp_container').width();
                    $('.sp_result_area').css('width', selectWidth);
                }
            },
        },
    }
    return Controller;
});