define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jquery-freezeheader'], function ($, undefined, Backend, Table, Form, undefined) {
// ,'echarts', 'echarts-theme'
// , Echarts, undefined
    var Controller = {
        consult: function () {
            var doesProjectShow = false;
            $(document).ready(function () {
                fixTableHNdF();

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    if ($(e.target).attr('href') == '#projectList') {
                        fixTableHNdF();
                        doesProjectShow = true;

                        $('a[data-toggle="tab"]').off('shown.bs.tab');
                    }
                })

                $(window).resize(function () {
                    fixTableHNdF();
                });

                Form.events.datetimepicker($('#f-commonsearch'));
                Form.events.selectpicker($('#f-commonsearch'));
            })

            function fixTableHNdF() {
                var offsetHeight = $('#myTab').offset().top + 45;
                var calcHeight = $(window).height() - offsetHeight-10;
                var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200);

                var realStaffTableHeight = $('#table-stat-staff').height();
                var realProjectTableHeight = $('#table-stat-project').height();

                if (realStaffTableHeight < tbodyHeight) {
                    $('#table-stat-staff tfoot').css('position', 'initial');
                } else {
                    $('#table-stat-staff').freezeHeader({ 'height': tbodyHeight + 'px' });

                    $('#table-stat-staff tfoot').css('position', 'absolute');
                    var ths = $('#table-stat-staff tr').eq(0).find('th');
                    var horizonalPadding = parseFloat(ths.eq(0).css('padding-left')) + parseFloat(ths.eq(0).css('padding-right'));
                    $('#table-stat-staff tfoot th').each(function(index) {
                        if (index == 0) {
                            $(this).width(ths.eq(index).width() + ths.eq(index + 1).width() + horizonalPadding);
                        } else {
                            $(this).width(ths.eq(index + 1).width());
                        }
                    })
                }

                if (realProjectTableHeight < tbodyHeight) {
                    $('#table-stat-project tfoot').css('position', 'initial');
                } else {
                    $('#table-stat-project').freezeHeader({ 'height': tbodyHeight + 'px' });
                    $('#table-stat-project tfoot').css('position', 'absolute');
                    var ths = $('#table-stat-project tr').eq(0).find('th');
                    $('#table-stat-project tfoot th').each(function(index) {
                        $(this).width(ths.eq(index).width());
                    })
                }
            }
             $('#btn-export').on('click', function() {
                var where = $('#currentFilter').text();
                var url = '/stat/Consultstat/downloadprocess' + '?where=' + encodeURI(where);

                Fast.api.open(url, __('Downloading page'));
            });
        },
         downloadprocess: function() {
            return Backend.api.commondownloadprocess('stat/consultrate/downloadprocess');
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
        }
    };
    return Controller;
});