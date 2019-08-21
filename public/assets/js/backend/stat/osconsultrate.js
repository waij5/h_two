define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-select'], function($, undefined, Backend, Table, Form,bootstrapSelect) {
    var Controller = {
        index: function() {
        	$('.selectpicker').selectpicker({  
                'selectedText': 'cat'  
            });
        	$('button[type="reset"]').click(function(){
        		$('.bootstrap-select').each(function(index) {
        		var searchId = $(this).find('.selectpicker').attr('name');
        		var defaultVal = $(this).find('.selectpicker').find('option').eq(0).html();
        		$(this).find('.dropdown-toggle').attr('title',defaultVal).attr('data-id',searchId).removeClass('bs-placeholder');
        		$(this).find('.dropdown-toggle').find('.filter-option').html(defaultVal);
        		$(this).find('.inner').find('li').eq(0).addClass('selected active');
        		$(this).find('.inner').find('li').eq(0).siblings('li').removeClass('selected active');
        		$(this).find('.inner').find('li').removeClass('hidden');
        		})
        	})
            Form.events.datetimepicker($('#f-commonsearch'));
            $('#a-search-customer').on('click', function() {
                // Fast.api.open('customer/');
                var params = '?mode=single';
                Fast.api.open('customer/customer/comselectpop' + params, __('Select customer'));
            });
            $('#btn-customer-clear').on('click', function() {
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            });
            $('button[type="reset"]').on('click', function() {
                $('.nickname').val('');
                $('#field_admin_id').val('');
                $('#field_ctm_id').val('');
                $('#field_ctm_name').val('');
            });
            Controller.api.bindStaffSelect();
 
            var postUrl = 'stat/osconsultrate/index';
            $('#btn-submit-1').on('click', function() {
                currentFilter = Backend.api.yjyGenerateParams($("form.form-commonsearch"), op);

                var loadLayerIndex = Layer.load();
                $.ajax({
                    url: postUrl,
                    data: {
                        filter: JSON.stringify(currentFilter),
                        op: JSON.stringify(op)
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#table tbody').empty();
                        $('#table tfoot').empty();

                        var index = 1;
                        for (var i in res.subs) {
                            Controller.api.generateRow(res.subs[i], 'td', index ++);
                        }

                        Controller.api.generateRow(res.total, 'th');

                        Layer.close(loadLayerIndex);
                    },
                    error: function(e) {
                        Layer.close(loadLayerIndex);
                        Layer.msg(__('Error occurs'), {
                            icon: 2
                        });
                    }
                });
            });

            $('#btn-export').on('click', function() {
                var url = '/stat/osconsultrate/downloadprocess' + '?op=' + encodeURI(JSON.stringify(op)) + '&filter=' + encodeURI(JSON.stringify(currentFilter));
                Fast.api.open(url, __('Downloading page'));
            });
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('stat/osconsultrate/downloadprocess');
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
            bindStaffSelect: function() {
                var tmpList = new Array();
                $('.nickname').keyup(function() {
                    var _this = $(this);
                    var keywords = $(this).val();
                    if (keywords == '') {
                        $('.word').empty().hide();
                        return;
                    };
                    var filter = JSON.stringify({
                        username: keywords
                    });
                    var op = JSON.stringify({
                        username: "LIKE %...%"
                    });
                    var fieldSpell = "username";
                    var username = "username";
                    $.ajax({
                        url: '/cash/order/staffquicksearch',
                        data: {
                            filter: filter,
                            op: op
                        },
                        dataType: 'json',
                        // jsonpCallback: 'fun', //回调函数名(值) value
                        beforeSend: function() {
                            $(_this).siblings().find('.word').append('<div>正在加载。。。</div>');
                        },
                        success: function(data) {
                            $(_this).siblings().find('.word').empty().show();
                            // console.log(data);
                            tmpList = data.rows;
                            if (data.total) {
                                for (var i in data.rows) {
                                    $(_this).siblings().find('.word').append('<li style="padding-top:5px" onmouseover="$(this).css(\'color\',\'#18bc9c\')" onmouseout="$(this).css(\'color\',\'#555555\')" data-index="' + i + '">' + data.rows[i]['nickname'] + '</li>');
                                }
                                $(_this).siblings().find('.word').show();
                            }
                        },
                        error: function() {
                            $(_this).siblings().find('.word').empty().show();
                            $(_this).siblings().find('.word').append('<div class="click_work">Fail "' + keywords + '"</div>');
                        }
                    })
                })
                $('.word').on('click', 'li', function() {
                    var i = $(this).data('index');
                    $('.nickname').val(tmpList[i]['nickname']);
                    $('#field_admin_id').val(tmpList[i]['id']);
                });
                $('#btn-admin-clear').on('click', function() {
                    $('.nickname').val('');
                    $('#field_admin_id').val('');
                });
            },
            generateRow: function (rowData, type, index) {
                var row = '<tr>' + 
                            '<' + type + '>' + (index ? index : '') + '</' + type + '>' + 
                            '<' + type + '>' + rowData['staffName'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['first_v_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['first_v_success_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['first_v_success_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['first_v_total'] + '</' + type + '>' + 

                            '<' + type + '>' + rowData['return_v_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['return_v_success_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['return_v_success_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['return_v_total'] + '</' + type + '>' + 

                            '<' + type + '>' + rowData['reconsume_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['reconsume_success_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['reconsume_success_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['reconsume_total'] + '</' + type + '>' + 
                            // 复查
                            '<' + type + '>' + rowData['review_v_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['review_v_success_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['review_v_success_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['review_v_total'] + '</' + type + '>' + 
                            // 其他
                            '<' + type + '>' + rowData['other_v_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['other_v_success_count'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['other_v_success_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['other_v_total'] + '</' + type + '>' + 

                            '<' + type + '>' + rowData['reception_total'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['success_total'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['success_total_rate'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['reception_percent'] + '</' + type + '>' + 

                            '<' + type + '>' + rowData['consumption_total'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['percent'] + '</' + type + '>' + 
                            '<' + type + '>' + rowData['consumption_per_person'] + '</' + type + '>' + 
                        '</tr>';
                $('#table tbody').append(row);
            }
        }
    };

    var currentFilter = {};
    var op = {
        'customer_id': '=',
        'osc.admin_id': '=',
        'osc.cpdt_id': '=',
        'osc.dept_id': '=',
        'osc.createtime': 'BETWEEN',
        'osc.osc_type': '=',
    };
    return Controller;
});