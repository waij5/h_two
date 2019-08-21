    define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jquery-freezeheader','echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, undefined, Echarts, undefined) {

    var Controller = {
        index: function () {
            $(document).ready(function () {
                fixTableHNdF();

                $(window).resize(function () {
                    fixTableHNdF();
                });
            })

            function fixTableHNdF() {
                var calcHeight = $(window).height() - 235;
                var tbodyHeight = (calcHeight >= 200 ? calcHeight : 200);
                var realTableHeight = $('#table').height();

                if (realTableHeight < tbodyHeight) {
                    $('#table tfoot').css('position', 'initial');
                } else {
                    $('#table').freezeHeader({ 'height': tbodyHeight + 'px'});
                    $('#table tfoot').css('position', 'absolute');
                    $('#table tfoot th').each(function(index) {
                        $(this).width($('#table tr').eq(1).find('th').eq(index).width());
                    })
                }
            }
            Form.events.datetimepicker($('#f-commonsearch'));

            var sort = 'stat_date';
            var op = {stat_date: 'BETWEEN'};
            var currentFilter = Backend.api.yjyGenerateParams($("form.form-commonsearch"), op);
            var url = '/stat/dailystat/downloadprocess?sort='
                + sort + '&order=ASC' + '&op=' + encodeURI(JSON.stringify(op)) + '&filter=' + encodeURI(JSON.stringify(currentFilter));
            $('#btn-export').on('click', function() {
                Fast.api.open(url, __('Downloading page'));
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        prostat2: function () {
            Form.events.datetimepicker($('#f-commonsearch'));

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
        },
        edit: function () {
            Controller.api.bindevent();
        },
        downloadprocess: function() {
            return Backend.api.commondownloadprocess('stat/dailystat/downloadprocess');
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            renderStatChart: function () {
                //图表高度
                var chartHeight = $(window).height() > 650 ? 650 : $(window).height * 0.85;
                $('#stat-chart').css('height', chartHeight);

                //图片数据处理
                var innerData = [];
                var outerData = [];
                var nameData = [];
                var curIndex = 1;
                for (var i in jsonStatData) {
                    innerData.push({value: jsonStatData[i].summary.pstat_total, name: jsonStatData[i].summary.cat_name});
                    nameData.push(jsonStatData[i].summary.cat_name);
                    for(var j in jsonStatData[i].sub) {
                        outerData.push({value: jsonStatData[i].sub[j].pstat_total, name: curIndex + jsonStatData[i].sub[j].cat_name});
                        nameData.push(curIndex + jsonStatData[i].sub[j].cat_name);
                        curIndex ++;
                    }
                }

                //图表配置生成
                mychart = Echarts.init($('#stat-chart')[0], 'walden');
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        x: 'left',
                        data:
                            nameData,
                    },
                    series: [
                        {
                            name:'营收统计',
                            type:'pie',
                            selectedMode: 'single',
                            radius: [0, '45%'],

                            label: {
                                normal: {
                                    position: 'inner'
                                }
                            },
                            labelLine: {
                                normal: {
                                    show: false
                                }
                            },
                            data:
                                innerData
                        },
                        {
                            name:'营收统计',
                            type:'pie',
                            radius: ['60%', '80%'],
                            label: {
                                normal: {
                                    formatter: '{a}\n{b}:{c}  {per|{d}%}',
                                    backgroundColor: '#eee',
                                    borderColor: '#aaa',
                                    borderWidth: 1,
                                    borderRadius: 4,
                                    rich: {
                                        a: {
                                            color: '#f00',
                                            lineHeight: 22,
                                            align: 'center'
                                        },
                                        abg: {
                                            backgroundColor: '#333',
                                            width: '100%',
                                            align: 'right',
                                            height: 22,
                                            borderRadius: [4, 4, 0, 0]
                                        },
                                        hr: {
                                            borderColor: '#aaa',
                                            width: '100%',
                                            borderWidth: 0.5,
                                            height: 0
                                        },
                                        b: {
                                            fontSize: 16,
                                            lineHeight: 33
                                        },
                                        per: {
                                            color: '#eee',
                                            backgroundColor: '#334455',
                                            padding: [2, 4],
                                            borderRadius: 2
                                        }
                                    }
                                }
                            },
                            data:
                                outerData
                        }
                    ]
                };
                mychart.setOption(option);
            }
        }
    };
    return Controller;
});