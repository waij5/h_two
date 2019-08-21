define(['jquery', 'bootstrap', 'backend', 'form', 'table'], function($, undefined, Backend, Form, Table) {
    var Controller = {
        index: function() {
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            },
        }
    };
    return Controller;
});