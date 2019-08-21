define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'base/wktype/index',
                    add_url: 'base/wktype/add',
                    edit_url: 'base/wktype/edit',
                    del_url: 'base/wktype/del',
                    multi_url: 'base/wktype/multi',
                    table: 'wktype',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                search: false,
                pk: 'zwt_id',
                sortName: 'zwt_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'zwt_id', title: __('Zwt_id')},
                        {field: 'zwt_code', title: __('Zwt_code')},
                        {field: 'zwt_name', title: __('Zwt_name')},
                        {field: 'zwt_percentage', title: __('Zwt_percentage')},
                        {field: 'zwt_otheramt', title: __('Zwt_otheramt')},
                        // {field: 'zwt_pamt', title: __('Zwt_pamt')},
                        {field: 'zwt_status', title: __('Zwt_status'), formatter: yjyFormatter.status},
                        // {field: 'zwt_sort', title: __('Zwt_sort')},
                        {field: 'zwt_remark', title: __('Zwt_remark')},
                        // {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: yjyFormatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    $('#status-switch').bootstrapSwitch({  
        onText:"正常",  
        offText:"禁用",  
        onColor:"success",  
        offColor:"danger",  
        size:"small",
        //初始开关状态
        state: $('#c-zwt_status').val() == 1 ? true: false,
        onSwitchChange:function(event,state){
            if(state==true){  
                $('#c-zwt_status').val(1);
            }else{  
                $('#c-zwt_status').val(0);
            }  
        }  
    })

    var yjyFormatter = {
        status: function (value, row, index, custom) {
            //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
            var colorArr = {1: 'success', 0: 'danger'};
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
        operate: function (value, row, index) {
            var table = this.table;
            // 操作配置
            var options = table ? table.bootstrapTable('getOptions') : {};
            // 默认按钮组
            var buttons = $.extend([], this.buttons || []);
            buttons.push({name: 'edit', icon: 'fa fa-pencil', classname: 'btn btn-xs btn-success btn-editone'});
            buttons.push({name: 'del', icon: 'fa fa-trash', classname: 'btn btn-xs btn-danger btn-delone'});
            var html = [];
            $.each(buttons, function (i, j) {
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
        }
    };

    return Controller;
});