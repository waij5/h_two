define(['jquery', 'bootstrap', 'front', 'dropload'], function($, undefined, Frontend, dropload) {
    var Controller = {
        index: function() {
            var page = 0;
            $('#y-l-main').dropload({
                scrollArea: window,
                domUp: {
                    domClass: 'dropload-up',
                    domRefresh: '<p class="dropload-refresh text-center">↓下拉刷新</p>',
                    domUpdate: '<p class="dropload-update text-center">↑释放更新</p>',
                    domLoad: '<p class="dropload-load text-center"><span class="loading"></span>加载中...</p>'
                },
                domDown: {
                    domClass: 'dropload-down',
                    domRefresh: '<p class="dropload-refresh text-center">↑上拉加载更多</p>',
                    domLoad: '<p class="dropload-load text-center"><span class="loading"></span>加载中...</p>',
                    domNoData: ''
                    // <p class="dropload-noData text-center">暂无数据</p>
                },
                loadUpFn: function(me) {
                    page = 1;
                    Frontend.yRequest({
                        url: location.href,
                        method: 'post',
                        data: {
                            page: page,
                            keyword: $.trim($('[name="keyword"]').val()),
                            _ajax: 1
                        },
                    }).then(res => {
                        if (res.code == 1) {
                            Controller.api.clearSetUl();
                            //render
                            for (proset of res.data.data) {
                                Controller.api.renderSetLi(proset);
                            }
                            if (page == res.data.last_page) {
                                me.lock();
                                me.noData();
                            }
                        } else {
                            layer.msg(res.msg ? res.msg : '出了小状况', {
                                icon: 2
                            });
                        }
                        me.resetload();
                    }).catch(e => {
                        console.log(e);
                        me.lock('down');
                        layer.msg('出了点小状况', {
                            icon: 2
                        });
                    });
                },
                loadDownFn: function(me) {
                    page++;
                    Frontend.yRequest({
                        url: location.href,
                        method: 'post',
                        data: {
                            page: page,
                            keyword: $.trim($('[name="keyword"]').val()),
                            _ajax: 1
                        },
                    }).then(res => {
                        if (res.code == 1) {
                            //render
                            for (proset of res.data.data) {
                                Controller.api.renderSetLi(proset);
                            }
                            if (page == res.data.last_page) {
                                me.lock();
                                me.noData();
                            }
                        } else {
                            layer.msg(res.msg ? res.msg : '出了小状况', {
                                icon: 2
                            });
                        }
                        me.resetload();
                    }).catch(e => {
                        me.lock('down');
                        layer.msg('出了点小状况', {
                            icon: 2
                        });
                    });
                }
            });
        },
        detail: function() {
            var fTagPosList = {};
            var fTagList = $('[id^=y-p-tab-]');
            fTagList.each(function() {
                var fTabOffset = $(this).offset();
                fTagPosList[$(this).attr('id')] = fTabOffset.top;
            });
            $(window).resize(function() {
                fTagList.each(function() {
                    var fTabOffset = $(this).offset();
                    fTagPosList[$(this).attr('id')] = fTabOffset.top;
                });
            });
            //初始化 左侧选中状态
            $(`#y-left-tags ul li:first-child a`).addClass('active');
            //滚动监听
            //WINDOW//
            // $('#y-left-tags').next('div').find('.tab-content')
            $(window).scroll(function() {
                var windowOffTop = $(window).scrollTop();
                var lastMatchTagName = '';
                for (var fTagName in fTagPosList) {
                    if (fTagPosList[fTagName] < windowOffTop) {
                        lastMatchTagName = fTagName;
                    } else {
                        break;
                    }
                }
                if (lastMatchTagName != '' && !$(`#y-left-tags ul li [href="#${lastMatchTagName}"]`).hasClass('active')) {
                    $('#y-left-tags ul li a.active').removeClass('active');
                    $(`#y-left-tags ul li [href="#${lastMatchTagName}"]`).addClass('active');
                    Controller.api.adjustUlOffset();
                }
            });
            $('#y-left-tags li a').on('click', function() {
                $('#y-left-tags li a.active').removeClass('active');
                $(this).addClass('active');
            });
            $('.y-set-qty-minus').on('click', function() {
                var curCartBaseQty = $('#y-cart-base-qty').text();
                if (curCartBaseQty <= 1) {
                    layer.msg('数量能小于1', {
                        icon: 2
                    });
                    return false;
                } else {
                    $('#y-cart-base-qty').text(parseInt(curCartBaseQty) - 1);
                }
            });
            $('.y-set-qty-plus').on('click', function() {
                var curCartBaseQty = $('#y-cart-base-qty').text();
                $('#y-cart-base-qty').text(parseInt(curCartBaseQty) + 1);
            });
            $('#y-btn-pre-checkout').on('click', function(e) {
                e.preventDefault();
                // Controller.goCheckout();
                if (Frontend.getChosenOsc()) {
                    //设置数量
                    var curCartBaseQty = parseInt($('#y-cart-base-qty').text());
                    $('.yjy-pro-choose-icon-pro.active').each(function() {
                        var qtyEle = $(this).parents('.yjy-pro-div-card').find('.yjy-input-qty');
                        $(qtyEle).val(parseInt($(qtyEle).data('base-qty')) * curCartBaseQty);
                    });
                    var suitIds = [];
                    $('.yjy-pro-choose-icon-set.active').each(function() {
                        var setId = $(this).parents('.yjy-pro-div-card').data('setId');
                        suitIds.push(setId);
                    });
                    $('[name=suits]').val(suitIds.join(','));
                    $('#form-pre-checkout').submit();
                } else {
                    layer.msg('请先选择接诊顾客', {
                        icon: 2
                    });
                }
            });
            $('#y-btn-add-to-cart').on('click', function(e) {
                e.preventDefault();
                // Controller.goCheckout();
                if (Frontend.getChosenOsc()) {
                    //设置数量
                    var curCartBaseQty = parseInt($('#y-cart-base-qty').text());
                    var isAllProSuc = true;
                    var isAllSetSuc = true;
                    $('.yjy-pro-choose-icon-pro.active').each(function() {
                        var loadLayerIndex = layer.load(4);
                        var proDiv = $(this).parents('.yjy-pro-div');
                        var qtyEle = $(proDiv).find('.yjy-input-qty');
                        var pk = $(proDiv).find('.y-pro-sub-pk').val();
                        // var deptId = $(proDiv).find('.y-pro-sub-dept_id').val();
                        // var proAmount = $(proDiv).find('.y-pro-sub-pro_amount').val();
                        var price = $(proDiv).find('.y-pro-sub-price').val();
                        // var proName = $(proDiv).find('.y-pro-sub-pro_name').val();
                        // var proSpec = $(proDiv).find('.y-pro-sub-pro_spec').val();
                        var qty = parseInt($(qtyEle).data('base-qty')) * curCartBaseQty;
                        if (qty > 0) {
                            var result = Frontend.addToCart({
                                pk: pk,
                                pro_id: pk,
                                // dept_id: deptId,
                                // pro_amount: proAmount,
                                price: price,
                                // pro_name: proName,
                                // pro_spec: proSpec,
                                qty: qty,
                            });
                            layer.close(loadLayerIndex);
                            if (result == false) {
                                isAllProSuc = false;
                            }
                        } else {
                            layer.close(loadLayerIndex);
                            layer.msg('数量有误', {
                                icon: 2
                            });
                            return false;
                        }
                    });
                    var suitIds = [];
                    $('.yjy-pro-choose-icon-set.active').each(function() {
                        var setId = $(this).parents('.yjy-pro-div-card').data('setId');
                        if (Frontend.addToCart(setId) == false) {
                            isAllSetSuc = false;
                        }
                    });
                    if (isAllProSuc && isAllSetSuc) {
                        layer.msg('更新购物车成功', {
                            icon: 1
                        });
                    } else {
                        layer.msg('更新购物车失败', {
                            icon: 2
                        });
                        return false;
                    }
                } else {
                    layer.msg('请先选择接诊顾客', {
                        icon: 2
                    });
                }
            });
            $('.yjy-pro-choose-icon-pro:not(.y-disabled)').on('click', function() {
                $(this).toggleClass('active');
                var qtyEle = $(this).parents('.yjy-pro-div-card').find('.yjy-input-qty');
                if ($(this).hasClass('active')) {
                    $(qtyEle).val($(qtyEle).data('base-qty'));
                } else {
                    $(qtyEle).val(0);
                }
            });
            $('.yjy-pro-choose-icon-set').on('click', function() {
                $(this).toggleClass('active');
            });
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
            $('#a-detail-back').on('click', function(e) {
                e.preventDefault();
                location.href = document.referrer ? document.referrer : '/web/set';
            });
        },
        previewset: function() {
            var fTagPosList = {};
            var fTagList = $('[id^=y-p-tab-]');
            fTagList.each(function() {
                var fTabOffset = $(this).offset();
                fTagPosList[$(this).attr('id')] = fTabOffset.top;
            });
            $(window).resize(function() {
                fTagList.each(function() {
                    var fTabOffset = $(this).offset();
                    fTagPosList[$(this).attr('id')] = fTabOffset.top;
                });
            });
            //初始化 左侧选中状态
            $(`#y-left-tags ul li:first-child a`).addClass('active');
            //滚动监听
            $(window).scroll(function() {
                var windowOffTop = $(window).scrollTop();
                var lastMatchTagName = '';
                for (var fTagName in fTagPosList) {
                    if (fTagPosList[fTagName] < windowOffTop) {
                        lastMatchTagName = fTagName;
                    } else {
                        break;
                    }
                }
                if (lastMatchTagName != '') {
                    $('#y-left-tags ul li a.active').removeClass('active');
                    $(`#y-left-tags ul li [href="#${lastMatchTagName}"]`).addClass('active');
                }
            });
            $('#y-left-tags li a').on('click', function() {
                $('#y-left-tags li a.active').removeClass('active');
                $(this).addClass('active');
            });
        },
        addAllToCart: function() {
            Frontend.updateCart();
        },
        goCheckout: function() {
            Frontend.clearCart();
            $('.yjy-pro-divs .yjy-pro-div').each(function(index, ele) {
                var cartItem = {
                    pro_id: $(ele).find('.yjy-input-pro-id').val(),
                    qty: $(ele).find('.yjy-input-qty').val(),
                    price: $(ele).find('.yjy-input-price').val(),
                }
                if (cartItem.qty > 0) {
                    Frontend.addToCart(cartItem);
                }
            });
            location.href = '/web/order/cart';
        },
        api: {
            getSetList: function(page) {
                $.ajax({
                    type: 'POST',
                    url: window.location.href,
                    data: {
                        page: page
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.code == 1) {
                            var data = res.data;
                            lastPage = data.last_page;
                            //render
                            for (proset of res.data.data) {
                                Controller.api.renderSetLi(proset);
                            }
                        } else {
                            layer.msg(res.msg ? res.msg : '出了小状况', {
                                icon: 2
                            });
                        }
                        // 每次数据加载完，必须重置
                        me.resetload();
                    },
                    error: function(xhr, type) {
                        layer.msg('出错了', {
                            icon: 2
                        });
                        // 即使加载出错，也得重置
                        me.resetload();
                    }
                });
            },
            clearSetUl: function() {
                $('#ul-sets-list').empty();
            },
            renderSetLi: function(proSet) {
                var video = proSet.video;
                var id = proSet.id;
                var pic = proSet.pic;
                var name = proSet.name;
                $('#ul-sets-list').append(`
                <li class="col-sm-6 col-md-4 yjy-pro-div" style="position: relative;">
                    <i class="fa fa-4x fa-play-circle yjy-pro-video-play ${video ? '' : ' hidden'}" onclick="yPlay('${video}')">
                    </i>
                    <div class="thumbnail">
                        <a href="/web/set/detail/ids/${id}">
                            <img alt="300x200" class="yjy-pro-img" src="${pic}"/>
                        </a>
                        <h4 class="text-center y-one-line">
                            ${name}
                        </h4>
                    </div>
                </li>
                `);
            },
            adjustUlOffset: function() {
                var tagOffset = $('#y-left-tags').offset();
                var tagHeight = $('#y-left-tags').height();
                var ulHeight = $('#y-left-tags ul').height();
                var ulOffset = $('#y-left-tags ul').offset();
                if (tagHeight < ulHeight) {
                    var activeEleOffset = $('#y-left-tags ul .active').offset();
                    var activeLiOffset = $('#y-left-tags ul .active').parents('li').offset();
                    var activeLiHeight = $('#y-left-tags ul .active').parents('li').height();
                    //too low 1 for tag border out of tag ul
                    if (tagOffset['top'] + tagHeight - 1 - activeLiHeight < activeLiOffset['top']) {
                        console.log('tagOffset ' + tagOffset['top']);
                        console.log('ulOffset ' + ulOffset['top']);
                        console.log('activeoffset ' + activeLiOffset['top']);
                        var newTop = ulOffset['top'] + (tagOffset['top'] + tagHeight - 1 - activeLiHeight - activeLiOffset['top']);
                        console.log('newTop ' + newTop);
                        $('#y-left-tags ul').offset({
                            top: newTop,
                            left: ulOffset['left']
                        });
                    } else {
                        //too high
                        if (tagOffset['top'] + 1 > activeLiOffset['top']) {
                            // var borderTB = 
                            var newTop = tagOffset['top'] + 1 + (activeLiOffset['top'] - (activeLiOffset['top'] + 1));
                            $('#y-left-tags ul').offset({
                                top: newTop,
                                left: ulOffset['left']
                            });
                        }
                    }
                }
            },
        },
    };
    return Controller;
});