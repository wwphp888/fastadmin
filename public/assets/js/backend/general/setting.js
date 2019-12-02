define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/setting/index',
                    add_url: 'general/config/add',
                    edit_url: 'general/setting/edit',
                    del_url: 'general/config/del',
                    multi_url: 'general/config/multi',
                    table: 'setting',
                }
            });

            var table = $("#setting");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'intro', title: __('Intro')},
                        {field: 'group', title: __('Group')},
                        {field: 'type', title: __('Type')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            $("form.edit-form").data("validator-options", {
                display: function (elem) {
                    return $(elem).closest('tr').find("td:first").text();
                }
            });
            Form.api.bindevent($("form.edit-form"));

            //添加向发件人发送测试邮件按钮和方法
            $('input[name="row[mail_from]"]').parent().next().append('<a class="btn btn-info testmail">' + __('Send a test message') + '</a>');
            $(document).on("click", ".testmail", function () {
                var that = this;
                Layer.prompt({title: __('Please input your email'), formType: 0}, function (value, index) {
                    Backend.api.ajax({
                        url: "general/config/emailtest",
                        data: $(that).closest("form").serialize() + "&receiver=" + value
                    });
                });

            });

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
    return Controller;
});