'use strict';
define(['fast', 'layer', 'cookie', 'qs'], function(Fast, layer, undefined, qs) {
    var Frontend = {
        apiBaseUrl: '/api/',
        api: Fast.api,
        state: {
            loginLayerIndex: 0,
            oscLayerIndex: 0,
            oscList: {}
        },
        yApi: {
            openMini: function(content) {
                var title = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
                var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
                return layer.open($.extend({
                    type: 1,
                    shadeClose: true,
                    shade: 0.3,
                    closeBtn: 0,
                    title: false,
                    content: content,
                    area: ['360px', '480px']
                }, options));
            },
            fixApiUrl: function(url) {
                url = $.trim(url);
                if (url.indexOf('http') != -1) {
                    return url;
                }
                return Frontend.apiBaseUrl + (url.indexOf('/') === 0 ? '/' + url.substring(1) : url);
            }
        },
        init: function() {
            $("[data-toggle='tooltip']").tooltip();
            //登录 退出相关
            $('#btn-staff-login').on('click', function() {
                Frontend.state.loginLayerIndex = Frontend.yApi.openMini($('#osc-staff-center').html());
            });
            $(document).on('click', '#y-btn-osc-login', function() {
                var username = $('.layui-layer-content #osc-staff-login input[name=username]').val();
                var password = $('.layui-layer-content #osc-staff-login input[name=password]').val();
                Frontend.login(username, password).then(function(res) {
                    if (res.code == 1) {
                        //关闭弹窗
                        layer.close(Frontend.state.loginLayerIndex);
                        //刷新页面 或 局部更新
                        window.location.reload(true);
                        // 方式2 Frontend.state.loginLayerIndex = 0;
                    }
                });
            });
            $(document).on('click', '#y-btn-osc-logout', function() {
                Frontend.clearStaffInfo();
                Frontend.clearChosenOsc();
                Frontend.clearCart();
                layer.msg('成功退出', {
                    icon: 1
                });
                window.location.reload();
            });
            Frontend.updateChoseOscView();
            //选择 分诊顾客
            $(document).on('click', '#btn-choose-osc', function(e) {
                // $('#osc-staff-center').html()
                Frontend.state.oscLayerIndex = Frontend.yApi.openMini($('#osc-choose-center').html(), false, {
                    area: ['640px', '480px'],
                    success: function(e) {
                        // console.log(e.selector);
                        //refresh 绑定，自动触发一次
                        $(e.selector + ' .y-pop-mini-refresh').on('click', function() {
                            var loadingindex = layer.load(3);
                            Frontend.yRequestWithToken({
                                url: '/customerosconsult/todayvisit'
                            }).then(function(res) {
                                var listHtml = '';
                                var data = res.data;
                                Frontend.state.oscList = {};
                                var chosenOsc = Frontend.getChosenOsc();
                                var chosenOscId = 0;
                                if (chosenOsc != false) {
                                    chosenOscId = chosenOsc.osc_id;
                                }
                                var _iteratorNormalCompletion = true;
                                var _didIteratorError = false;
                                var _iteratorError = undefined;
                                try {
                                    for (var _iterator = data[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                                        var rowData = _step.value;
                                        Frontend.state.oscList[rowData.osc_id] = {
                                            osc_id: rowData.osc_id,
                                            customer_id: rowData.customer_id,
                                            ctm_name: rowData.ctm_name
                                        };
                                        // escape
                                        var name = rowData.ctm_name;
                                        var gender = '女';
                                        var genderImg = 'female.png';
                                        if (rowData.ctm_sex == 2) {
                                            gender = '男';
                                            genderImg = 'male.png';
                                        }
                                        // ${rowData.ctm_birthdate}
                                        var activeCls = rowData.osc_id == chosenOscId ? ' active' : '';
                                        listHtml += '\n                                        <div class="osc-choose-item' + activeCls + '" data-osc-id="' + rowData.osc_id + '">\n                                            <img src="/assets/img/' + genderImg + '" class="osc-choose-item-avatar" />\n                                            <div class="osc-choose-item-r-header">\n                                                <span class="y-title20">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;</span>\n                                                <small>\n                                                    &nbsp;&nbsp;&nbsp;&nbsp;\n                                                </small>\n                                            </div>\n                                            <div class="osc-choose-item-r-content">\n                                                <i class="y-color-main fa fa-fw fa-mobile"></i>&nbsp;' + rowData.ctm_mobile + '\n                                                &nbsp;&nbsp;\n                                                <i class="y-color-main fa fa-fw fa-phone"></i>&nbsp;' + rowData.ctm_mobile + '\n                                                &nbsp;&nbsp;&nbsp;&nbsp;\n                                                <i class="y-color-main fa fa-fw fa-vcard"></i>&nbsp;' + rowData.ctm_id + '\n                                            </div>\n                                        </div>\n                                    ';
                                    }
                                } catch (err) {
                                    _didIteratorError = true;
                                    _iteratorError = err;
                                } finally {
                                    try {
                                        if (!_iteratorNormalCompletion && _iterator.return) {
                                            _iterator.return();
                                        }
                                    } finally {
                                        if (_didIteratorError) {
                                            throw _iteratorError;
                                        }
                                    }
                                }
                                $(e.selector + ' #osc-choose-list').html(listHtml);
                                layer.close(loadingindex);
                                $(e.selector + ' #osc-choose-list .osc-choose-item').on('click', function() {
                                    $(e.selector + ' #osc-choose-list .osc-choose-item.active').removeClass('active');
                                    $(this).addClass('active');
                                    //todo set choosed osc
                                    Frontend.setChosenOsc(Frontend.state.oscList[$(this).data('oscId')]);
                                    //clear cart after choose new osc
                                    Frontend.clearCart();
                                    Frontend.state.oscLayerIndex && layer.close(Frontend.state.oscLayerIndex);
                                    //update chose customer card
                                    Frontend.updateChoseOscView();
                                });
                            }).catch(function(e) {
                                console.log(e);
                                layer.close(loadingindex);
                            });
                        });
                        $(e.selector + ' .y-pop-mini-refresh').trigger('click');
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            });
            //跳转购物车按钮
            $(document).on('click', '#btn-show-cart', function() {
                $('#y-common-form-to-cart').submit();
            });
            $(document).on('click', '#y-btn-clear-cart', function() {
                Frontend.clearCart();
                layer.msg('清理购物车成功', {
                    icon: 1
                });
                if ($('#y-v-use-cookie').length > 0 && $('#y-v-use-cookie').val() > 0 && $('#table-cart tbody').length > 0) {
                    $('#table-cart tbody').empty();
                }
            })
            //H5 播放
            $(document).on('click', '.yjy-pro-video-play', function() {});
        },
        //获取职员信息
        getStaffInfo: function() {
            return $.cookie('staffInfo') ? JSON.parse($.cookie('staffInfo')) : false;
        },
        //设置职员信息
        setStaffInfo: function(staffInfo) {
            $.cookie('staffInfo', JSON.stringify(staffInfo), {
                expires: 14,
                path: '/web'
            });
        },
        //清除职员信息
        clearStaffInfo: function() {
            $.cookie('staffInfo', null, {
                path: '/web',
                expires: -1
            });
        },
        getChosenOsc: function() {
            return $.cookie('chosenOsc') ? JSON.parse($.cookie('chosenOsc')) : false;
        },
        setChosenOsc: function(chosenOsc) {
            //明天失效
            var utcTomorrow = new Date();
            utcTomorrow.setDate(utcTomorrow.getDate() + 1);
            var fullYear = utcTomorrow.getFullYear();
            var month = utcTomorrow.getMonth() + 1;
            var date = utcTomorrow.getDate();
            var tomorrow = new Date(fullYear + '-' + month + '-' + date + ' 00:00:00');
            $.cookie('chosenOsc', JSON.stringify(chosenOsc), {
                expires: tomorrow,
                path: '/web'
            });
        },
        clearChosenOsc: function() {
            $.cookie('chosenOsc', null, {
                path: '/web',
                expires: -1
            });
        },
        updateChoseOscView: function() {
            var chosenOsc = {};
            if ((chosenOsc = Frontend.getChosenOsc()) != false && $('#chosen-osc-customer').length > 0) {
                $('#chosen-osc-customer').text(chosenOsc.ctm_name);
            }
        },
        //登录
        login: function(uname, password) {
            uname = $.trim(uname);
            password = $.trim(password);
            if (uname == '') {
                layer.msg('请输入帐户', {
                    icon: 2
                });
                return false;
            }
            if (password == '') {
                layer.msg('请输入密码', {
                    icon: 2
                });
                return false;
            }
            return Frontend.yRequest({
                url: 'passport/login',
                method: 'POST',
                data: {
                    username: uname,
                    password: password,
                    authType: 'web'
                }
            }).then(function(data) {
                if (data.code == 1) {
                    layer.msg(!!data.msg ? data.msg : '登录成功', {
                        icon: 1
                    });
                    Frontend.setStaffInfo(data.data);
                } else {
                    layer.msg(!!data.msg ? data.msg : '登录失败', {
                        icon: 2
                    });
                }
                return data;
            }).catch(function(err) {
                layer.msg('登录失败', {
                    icon: 2
                });
            });
        },
        yRequestWithToken: function(_ref) {
            var url = _ref.url,
                _ref$method = _ref.method,
                method = _ref$method === undefined ? 'POST' : _ref$method,
                _ref$headers = _ref.headers,
                headers = _ref$headers === undefined ? [] : _ref$headers,
                _ref$params = _ref.params,
                params = _ref$params === undefined ? [] : _ref$params,
                _ref$data = _ref.data,
                data = _ref$data === undefined ? [] : _ref$data;
            return new Promise(function(resolve, reject) {
                var staffInfo = Frontend.getStaffInfo();
                if (staffInfo) {
                    resolve(staffInfo);
                } else {
                    layer.msg('请先点击右上角登录', {
                        icon: 2
                    });
                    reject('未登录');
                }
            }).then(function(staffInfo) {
                return Frontend.yRequest({
                    url: url,
                    method: method,
                    headers: headers,
                    params: params,
                    data: data,
                    access_token: 'Bearer ' + staffInfo['accessToken']
                });
            });
        },
        // API 请求
        yRequest: function(_ref2) {
            var url = _ref2.url,
                _ref2$method = _ref2.method,
                method = _ref2$method === undefined ? 'GET' : _ref2$method,
                _ref2$headers = _ref2.headers,
                headers = _ref2$headers === undefined ? [] : _ref2$headers,
                _ref2$params = _ref2.params,
                params = _ref2$params === undefined ? [] : _ref2$params,
                _ref2$data = _ref2.data,
                data = _ref2$data === undefined ? [] : _ref2$data,
                _ref2$access_token = _ref2.access_token,
                access_token = _ref2$access_token === undefined ? '' : _ref2$access_token;
            var DEFAULT_HEADERS = {
                'Content-Type': 'application/json;charset=utf-8'
            };
            var isOk = void 0;
            url = Frontend.yApi.fixApiUrl(url);
            if (params) {
                url = url + '?' + qs.stringify(params, {
                    arrayFormat: 'brackets'
                });
            }
            // var headers = {
            //     ...DEFAULT_HEADERS,
            //     ...headers,
            //     ...Authorization: access_token
            // };
            var headers = $.extend({}, DEFAULT_HEADERS, headers, {
                Authorization: access_token
            });
            if (headers['Content-Type'] && headers['Content-Type'].indexOf('json') != -1) {
                data = JSON.stringify(data);
            } else {
                //formdata需要
                if (headers['Content-Type'] == '' || headers['Content-Type'] == null) {
                    delete headers['Content-Type'];
                }
            }
            return fetch(url, {
                method: method,
                headers: headers,
                body: data
            }).then(function(response) {
                if (!!response.ok) {
                    return response.json();
                } else {
                    layer.msg('网络故障', {
                        icon: 2
                    });
                }
            });
        },
        checkStorage: function() {
            var showWarning = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
            var canUseStorage = typeof sessionStorage != 'undefined';
            if (showWarning && !canUseStorage) {
                alert('您的浏览器不支持相应功能');
            }
            return canUseStorage;
        },
        setStorageItem: function(name, value) {
            if (Frontend.checkStorage(true)) {
                try {
                    sessionStorage.setItem(name, value);
                    return true;
                } catch (e) {
                    alert('您处于无痕浏览，无法为您保存，请切回正常模式');
                }
            }
            return false;
        },
        getStorageItem: function(name) {
            if (Frontend.checkStorage(true)) {
                try {
                    return sessionStorage.getItem(name);
                } catch (e) {
                    alert('您处于无痕浏览，无法为您保存，请切回正常模式');
                }
            }
        },
        removeStorageItem: function(name) {
            if (Frontend.checkStorage(true)) {
                try {
                    sessionStorage.removeItem(name);
                    return true;
                } catch (e) {
                    alert('您处于无痕浏览，无法为您保存，请切回正常模式');
                }
            }
            return false;
        },
        //获取今日分诊顾客列表
        getTodayCusList: function() {},
        //选择顾客
        chooseCustomer: function() {},
        getCart: function() {
            // var cartsStr = Frontend.getStorageItem('ycart');
            var cartsStr = $.cookie('ycart') ? $.cookie('ycart') : false;
            var cartsList = {
                pros: {},
                sets: []
            };
            if (cartsStr) {
                try {
                    cartsList = JSON.parse(cartsStr);
                    if (typeof cartsList['pros'] == 'undefined' || typeof cartsList['sets'] == 'undefined') {
                        // Frontend.setStorageItem('ycart', JSON.stringify(cartsList));
                        return cartsList;
                    }
                } catch (e) {
                    layer.msg('购物车初始化失败', {
                        icon: 2
                    });
                }
            }
            return cartsList;
        },
        setCart: function(cartsList) {
            //明天失效
            var utcTomorrow = new Date();
            utcTomorrow.setDate(utcTomorrow.getDate() + 1);
            var fullYear = utcTomorrow.getFullYear();
            var month = utcTomorrow.getMonth() + 1;
            var date = utcTomorrow.getDate();
            var tomorrow = new Date(fullYear + '-' + month + '-' + date + ' 00:00:00');
            return $.cookie('ycart', JSON.stringify(cartsList), {
                expires: tomorrow,
                path: '/web'
            });
        },
        //清空购物车
        clearCart: function() {
            $.cookie('ycart', null, {
                path: '/web',
                expires: -1
            });
        },
        delCartItem: function(type, id) { //检查 删除数据 重新计算
            // typeof Controller.data.cartItemList[proId] != 'undefined' && delete Controller.data.cartItemList[proId] && Controller.api.recalcTotal();
            var carts = Frontend.getCart();
            if (type == 'sets') {
                if (carts['sets'].indexOf(id) != -1) {
                    carts['sets'].splice(carts['sets'].indexOf(id), 1);
                }
            } else {
                if (typeof carts['pros'][id] != 'undefined') {
                    delete carts['pros'][id];
                }
            }
            Frontend.setCart(carts);
        },
        //添加到购物车
        addToCart: function(cartItem) {
            var cartsList = Frontend.getCart();
            if (typeof cartItem != 'object') {
                //set
                if (!cartsList['sets'].includes(cartItem)) {
                    cartsList['sets'].push(cartItem);
                    Frontend.setCart(cartsList);
                }
                return true;
            } else {
                //pro
                var oldQty = 0;
                if (typeof cartsList['pros'][cartItem.pro_id] != 'undefined' && typeof cartsList['pros'][cartItem.pro_id]['qty'] != 'undefined') {
                    var oldQty = cartsList['pros'][cartItem.pro_id]['qty'];
                }
                cartItem['qty'] = parseInt(cartItem['qty']) + parseInt(oldQty);
                cartsList['pros'][cartItem.pro_id] = cartItem;
                Frontend.setCart(cartsList);
            }
            try {
                return true;
            } catch (e) {
                return false;
            }
            // if (Frontend.setStorageItem('ycart', JSON.stringify(cartsList))) {
            //     return true;
            // } else {
            //     return false;
            // }
        },
        //更新购物车
        updateCart: function(cartItem) {
            return Frontend.addToCart(cartItem, 'update');
        },
        //提交订单
        saveOrder: function() {}
    };
    Frontend.api = $.extend(Fast.api, Frontend.api);
    //将Frontend渲染至全局,以便于在子框架中调用
    window.Frontend = Frontend;
    Frontend.init();
    return Frontend;
});