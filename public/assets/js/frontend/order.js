define(['jquery', 'bootstrap', 'front', 'validator'], function($, undefined, Frontend, Validator) {
    var Controller = {
        data: {
            cartItemList: {}
        },
        cart: function cart() {
            if (history && history.replaceState) {
                if (document.referrer && document.referrer == location.href) {
                    
                }
            }
            // Frontend.login('admin', '123456');
            var oscInfo = Frontend.getChosenOsc();
            if (oscInfo) {
                $('#t-cart-osc-info').text("姓名：".concat(oscInfo.ctm_name, " | 卡号 ").concat(oscInfo.customer_id));
            } else {
                layer.msg('未选择分诊顾客信息', {});
            }
            $('.y-preview-set').on('click', function(e) {
                e.preventDefault();
                // Frontend.api.open($(this).data('href'), 'ddd');
                options = {};
                var area = [$(window).width() * 0.8 + 'px', $(window).height() * 0.8 + 'px'];
                return layer.open($.extend({
                    type: 2,
                    shadeClose: true,
                    shade: 0.3,
                    closeBtn: 0,
                    title: false,
                    content: $(this).data('href'),
                    area: area
                }, options));
            });
            Controller.api.recalcTotal();
            $('.y-btn-remove-cart-item').each(function() {
                $(this).on('click', function() {
                    var _this = this;
                    var lIndex = layer.confirm('确定删除吗？', function() {
                        var proId = $(_this).parents('tr').data('proId');
                        //购物车时 额外更新购物车
                        try {
                            if ($('#y-v-use-cookie').val() > 0) {
                                // Frontend.clearCart();
                                if ($(_this).parents('tr').data('proId')) {
                                    Frontend.delCartItem('pros', $(_this).parents('tr').data('proId'));
                                } else {
                                    Frontend.delCartItem('sets', $(_this).parents('tr').data('setId'));
                                }
                            }
                        } catch(e) {
                            console.log(e);
                        }
                        
                        $(_this).parents('tr').remove();
                        Controller.api.recalcTotal();
                        layer.close(lIndex);
                    });
                });
            });
            $('.y-cart-c-base').each(function() {
                $(this).on('change', function() {
                    var calcBaseType = false;
                    if ($(this).hasClass('y-cart-row-qty')) {
                        calcBaseType = 'qty';
                    } else {
                        if ($(this).hasClass('y-cart-row-total')) {
                            calcBaseType = 'total';
                        }
                    }
                    if (calcBaseType == false) {
                        layer.msg('购物车重新计算出错', {
                            icon: 2
                        });
                        return false;
                    }
                    var row = $(this).parents('tr');
                    return Controller.api.recalcRowTotal(calcBaseType, row);
                });
            });
            $('#form-checkout').validator($.extend({
                validClass: 'has-success',
                invalidClass: 'has-error',
                // bindClassTo: '.form-group',
                // formClass: 'n-default n-bootstrap',
                msgClass: 'n-right',
                msgIcon: '<span class="fa fa-info-circle"></span>',
                msgStyle: "position: absolute; top: 0; right: 0; color: red;",
                stopOnError: true,
                // display: function (elem) {
                //     return $(elem).closest('.form-group').find(".control-label").text().replace(/\:/, '');
                // },
                target: function target(input) {
                    var $formitem = $(input).closest('.td'),
                        $msgbox = $formitem.find('span.y-msg-box');
                    if (!$msgbox.length) {
                        return [];
                    }
                    return $msgbox;
                },
                valid: function valid(ret) {
                    var me = this;
                    var staffInfo = Frontend.getStaffInfo();
                    if (staffInfo == false) {
                        layer.msg('请先登录', {
                            icon: 2
                        });
                        return false;
                    }
                    var choseOsc = Frontend.getChosenOsc(); //检查分诊
                    if (choseOsc == false) {
                        layer.msg('请先选择接诊顾客', {
                            icon: 2
                        });
                        return false;
                    }
                    (new Promise(function(resolve, reject) {
                        if (staffInfo.position > 0) {
                            resolve('');
                        } else {
                            resolve('');
                            //价格审批
                            /*layer.prompt({
                                formType: 2,
                                value: '',
                                title: '填写价格审批申请【超出自身折扣时必填】',
                                // area: ['800px', '350px'] //自定义文本域宽高
                            }, function(value, index, elem) {
                                resolve(value);
                            });*/
                        }
                    })).then(function(applyInfo) {
                        me.holdSubmit(true);
                        var formdata = new FormData($("#form-checkout")[0]);
                        formdata.append('osconsult_id', choseOsc.osc_id);
                        formdata.append('row[customer_id]', choseOsc.customer_id);
                        // formdata.append('applyInfo', applyInfo);
                        Frontend.yRequestWithToken({
                            url: '/order/create',
                            data: formdata,
                            headers: {
                                'Content-Type': null
                            }
                        }).then(function(res) {
                            if (res.code == 1) {
                                //购物车提交，提交成功后清空购物车
                                if ($('#y-v-use-cookie').val() > 0) {
                                    Frontend.clearCart();
                                }
                                location.href = '/web/order/csuccess';
                            } else {
                                layer.msg(res.msg ? res.msg : '提交失败', {
                                    icon: 2
                                });
                            }
                            me.holdSubmit(false);
                        }).catch(function(err) {
                            me.holdSubmit(false);
                            layer.msg('出错了', {
                                icon: 2
                            });
                            console.log(err);
                        });
                        layer.close(index);
                    })
                    // //价格审批
                    // layer.prompt({
                    //     formType: 2,
                    //     value: '',
                    //     title: '填写价格审批申请【超出自身折扣时必填】',
                    //     // area: ['800px', '350px'] //自定义文本域宽高
                    // }, function(value, index, elem) {
                    // });
                    return false;
                }
            }, $('#form-checkout').data("validator-options") || {}));
            $('#y-btn-pre-checkout').on('click', function(e) {
                e.preventDefault;
                if ('#form-checkout tbody tr'.length > 0) {
                    $('#form-checkout').submit();
                } else {
                    layer.msg('没有要提交的项目', {
                        icon: 2
                    });
                    return false;
                }
            });
        },
        api: {
            setCartItem: function setCartItem(cartItem) {
                Controller.data.cartItemList[cartItem.pro_id] = cartItem;
            },

            // recalcTotal
            recalcTotal: function () {
                var total = 0.00;
                $('.y-cart-row-total').each(function(index, ele) {
                    total += parseFloat($(ele).val());
                });
                $('#y-cart-total').text(total.toFixed(2));
            },
            checkRowTotal: function (row) {
                var priceEle = $(row).find('.y-cart-row-price'),
                    qtyEle = $(row).find('.y-cart-row-qty'),
                    totalEle = $(row).find('.y-cart-row-total');
            },
            // recalcRowTotal
            recalcRowTotal: function (type, row) {
                console.log(type, row);
                if (type != 'qty' && type != 'total') {
                    return false;
                }
                var priceEle = $(row).find('.y-cart-row-price'),
                    qtyEle = $(row).find('.y-cart-row-qty'),
                    totalEle = $(row).find('.y-cart-row-total');
                var price = parseFloat(priceEle.val()),
                    qty = parseInt(qtyEle.val()),
                    total = parseFloat(totalEle.val()),
                    recalcFlg = false;
                console.log(type, row, priceEle, qtyEle, totalEle, price, qty, total, recalcFlg);
                if (type == 'qty') {
                    totalEle.val((price * qty).toFixed(2));
                    recalcFlg = true;
                } else {
                    if (type == 'total') {
                        if (qty > 0) {
                            //中间价格 多保留两位小数，保证计算精度
                            priceEle.val((total / qty).toFixed(4));
                            recalcFlg = true;
                        }
                    }
                }
                if (recalcFlg) {
                    Controller.api.recalcTotal();
                }
            }
        }
    };
    return Controller;
});