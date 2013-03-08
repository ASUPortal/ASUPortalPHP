/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 16.12.12
 * Time: 13:19
 * To change this template use File | Settings | File Templates.
 */
(function ($) {
    $.fn.namesSelector = function(){
        var fieldName = $(this).attr("id");

        // добавляем к выбиратору с лупой открывание диалога
        var selector = $("#" + fieldName + "_selector");
        $(selector).css("cursor", "pointer");
        $(selector).bind("click", function() {
            var items = new Array();
            var arr_ids = new Array();
            var arr_names = new Array();
            var arr_types = new Array();
            // получаем выбранных на момент нажатия людей
            $.each($('input[name="' + fieldName + '[name][]"]'), function(key, value){
                arr_names[arr_names.length] = $(value).val();
            });
            $.each($('input[name="' + fieldName + '[id][]"]'), function(key, value){
                arr_ids[arr_ids.length] = $(value).val();
            });
            $.each($('input[name="' + fieldName + '[type][]"]'), function(key, value){
                arr_types[arr_types.length] = $(value).val();
            });
            $.each(arr_names, function(key, value) {
                var obj = new Object();
                obj.id = arr_ids[key];
                obj.name = arr_names[key];
                obj.type = arr_types[key];
                items[items.length] = obj;
            });

            $.ajax({
                url: web_root + "_modules/_acl_manager/?action=personSelectDialog",
                data: {
                    items: items,
                    field: fieldName
                },
                type: "post"
            }).done(function(html){
                var dialog = $("<div>").html(html).dialog({
                    width: 700,
                    height: 340,
                    title: "Выбор людей",
                    modal: true,
                    buttons: {
                        "Отмена": function() {
                            $(this).dialog("close");
                        },
                        "Сохранить": function() {
                            // собираем данные из диалога и кладем их в поля документа
                            var arr_ids = new Array();
                            var arr_names = new Array();
                            var arr_types = new Array();
                            var items = new Array();
                            // получаем выбранных на момент нажатия людей
                            $.each($('input[name="dialog[name][]"]'), function(key, value){
                                arr_names[arr_names.length] = $(value).val();
                            });
                            $.each($('input[name="dialog[id][]"]'), function(key, value){
                                arr_ids[arr_ids.length] = $(value).val();
                            });
                            $.each($('input[name="dialog[type][]"]'), function(key, value){
                                arr_types[arr_types.length] = $(value).val();
                            });
                            $.each(arr_names, function(key, value) {
                                var obj = new Object();
                                obj.id = arr_ids[key];
                                obj.name = arr_names[key];
                                obj.type = arr_types[key];
                                items[items.length] = obj;
                            });
                            var field_name = $($(this).find("#dialog_field")[0]).val();
                            var parent = $("#" + field_name).parent();
                            // удаляем параллельные поля диалога из документа
                            $.each($('input[name="' + field_name + '[type][]"]'), function(key, value) {
                                $(value).remove();
                            });
                            $.each($('input[name="' + field_name + '[id][]"]'), function(key, value) {
                                $(value).remove();
                            });
                            $.each($('input[name="' + field_name + '[name][]"]'), function(key, value) {
                                $(value).remove();
                            });
                            var select = $("#" + field_name + "_select");
                            $(select).empty();
                            // заполняем параллельные поля
                            $.each(items, function(key, value) {
                                var option = $("<option>");
                                option.attr({
                                    "value": value.id
                                }).text(value.name);
                                $(select).append(option);
                                var inputId = $("<input>");
                                inputId.attr({
                                    "type": "hidden",
                                    "name": field_name + "[id][]",
                                    "value": value.id
                                });
                                $(parent).append(inputId);
                                var inputType = $("<input>");
                                inputType.attr({
                                    "type": "hidden",
                                    "name": field_name + "[type][]",
                                    "value": value.type
                                });
                                $(parent).append(inputType);
                                var inputName = $("<input>");
                                inputName.attr({
                                    "type": "hidden",
                                    "name": field_name + "[name][]",
                                    "value": value.name
                                });
                                $(parent).append(inputName);
                            });
                            // закрываем диалог
                            $(this).dialog("close");
                        }
                    }
                });
                var users = $(dialog).find("#dialog_users")[0];
                var groups = $(dialog).find("#dialog_groups")[0];
                // вешаем на ссылки с группами и пользователями открывалку диалогов
                $(users).click(function() {
                    $.ajax({
                        url: web_root + "_modules/_acl_manager/?action=userSelectDialog",
                        type: "post"
                    }).done(function(html){
                        var userDialog = $("<div>").html(html).dialog({
                            width: 500,
                            height: 400,
                            title: "Выбор пользователей",
                            modal: true,
                            buttons: {
                                "Отмена": function() {
                                    $(this).dialog("close");
                                },
                                "ОК": function() {
                                    // складываем выбранных людей обратно в диалог выбора людей
                                    // получаем родительский диалог
                                    var options = $(this).dialog("option");
                                    var parent = $(options.parentDialog);
                                    // получаем выбранные элементы в текущем диалоге
                                    var selected = $(this).find("input[type=checkbox]:checked");
                                    // получаем ранее выбранные элементы
                                    var items = new Array();
                                    var arr_ids = new Array();
                                    var arr_names = new Array();
                                    var arr_types = new Array();
                                    // получаем выбранных на момент нажатия людей
                                    $.each($('input[name="dialog[name][]"]'), function(key, value){
                                        arr_names[arr_names.length] = $(value).val();
                                    });
                                    $.each($('input[name="dialog[id][]"]'), function(key, value){
                                        arr_ids[arr_ids.length] = $(value).val();
                                    });
                                    $.each($('input[name="dialog[type][]"]'), function(key, value){
                                        arr_types[arr_types.length] = $(value).val();
                                    });
                                    $.each(arr_names, function(key, value) {
                                        var obj = new Object();
                                        obj.id = arr_ids[key];
                                        obj.name = arr_names[key];
                                        obj.type = arr_types[key];
                                        items[items.length] = obj;
                                    });
                                    // исключаем из выбранных в диалоге элементов уже имеющиеся в диалоге
                                    $.each(items, function(key, item){
                                        $.each(selected, function(key, newItem) {
                                            if (item.id == $(newItem).val()) {
                                                if (item.type == 1) {
                                                    selected.splice(key, 1);
                                                }
                                            }
                                        });
                                    });
                                    // добавляем оставшиеся
                                    $.each(selected, function(key, value){
                                        var obj = new Object();
                                        obj.id = $(value).val();
                                        obj.type = 1;
                                        obj.name = $(value).attr("rus");
                                        items[items.length] = obj;
                                    });
                                    // очищаем выбираторы родительского диалога
                                    var selector = $(parent).find("#dialog_names")[0];
                                    $(selector).empty();
                                    // убиваем параллельные поля и заново их создаем
                                    $.each($(parent).find('input[name="dialog[type][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    $.each($(parent).find('input[name="dialog[id][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    $.each($(parent).find('input[name="dialog[name][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    // попробуем добавить option в выбиратор и создать параллельные поля
                                    $.each(items, function(key, value){
                                        var option = $("<option>");
                                        option.attr({
                                            "value": value.id
                                        }).text(value.name);
                                        $(selector).append(option);
                                        var inputId = $("<input>");
                                        inputId.attr({
                                            "type": "hidden",
                                            "name": "dialog[id][]",
                                            "value": value.id
                                        });
                                        $(parent).append(inputId);
                                        var inputType = $("<input>");
                                        inputType.attr({
                                            "type": "hidden",
                                            "name": "dialog[type][]",
                                            "value": value.type
                                        });
                                        $(parent).append(inputType);
                                        var inputName = $("<input>");
                                        inputName.attr({
                                            "type": "hidden",
                                            "name": "dialog[name][]",
                                            "value": value.name
                                        });
                                        $(parent).append(inputName);
                                    });
                                    $(this).dialog("close");
                                }
                            }
                        });
                        // сохраняем родительский диалог в свойстве parentDialog
                        $(userDialog).dialog("option", {
                            parentDialog: dialog
                        });
                    });
                });
                $(groups).click(function(){
                    $.ajax({
                        url: web_root + "_modules/_acl_manager/?action=groupSelectDialog",
                        type: "post"
                    }).done(function(html){
                        var groupDialog = $("<div>").html(html).dialog({
                            width: 500,
                            height: 400,
                            title: "Выбор групп",
                            modal: true,
                            buttons: {
                                "Отмена": function() {
                                    $(this).dialog("close");
                                },
                                "ОК": function() {
                                    // складываем выбранных людей обратно в диалог выбора людей
                                    // получаем родительский диалог
                                    var options = $(this).dialog("option");
                                    var parent = $(options.parentDialog);
                                    // получаем выбранные элементы в текущем диалоге
                                    var selected = $(this).find("input[type=checkbox]:checked");
                                    // получаем ранее выбранные элементы
                                    var items = new Array();
                                    var arr_ids = new Array();
                                    var arr_names = new Array();
                                    var arr_types = new Array();
                                    // получаем выбранных на момент нажатия людей
                                    $.each($('input[name="dialog[name][]"]'), function(key, value){
                                        arr_names[arr_names.length] = $(value).val();
                                    });
                                    $.each($('input[name="dialog[id][]"]'), function(key, value){
                                        arr_ids[arr_ids.length] = $(value).val();
                                    });
                                    $.each($('input[name="dialog[type][]"]'), function(key, value){
                                        arr_types[arr_types.length] = $(value).val();
                                    });
                                    $.each(arr_names, function(key, value) {
                                        var obj = new Object();
                                        obj.id = arr_ids[key];
                                        obj.name = arr_names[key];
                                        obj.type = arr_types[key];
                                        items[items.length] = obj;
                                    });
                                    // исключаем из выбранных в диалоге элементов уже имеющиеся в диалоге
                                    $.each(items, function(key, item){
                                        $.each(selected, function(key, newItem) {
                                            if (item.id == $(newItem).val()) {
                                                if (item.type == 2) {
                                                    selected.splice(key, 1);
                                                }
                                            }
                                        });
                                    });
                                    // добавляем оставшиеся
                                    $.each(selected, function(key, value){
                                        var obj = new Object();
                                        obj.id = $(value).val();
                                        obj.type = 2;
                                        obj.name = $(value).attr("rus");
                                        items[items.length] = obj;
                                    });
                                    // очищаем выбираторы родительского диалога
                                    var selector = $(parent).find("#dialog_names")[0];
                                    $(selector).empty();
                                    // убиваем параллельные поля и заново их создаем
                                    $.each($(parent).find('input[name="dialog[type][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    $.each($(parent).find('input[name="dialog[id][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    $.each($(parent).find('input[name="dialog[name][]"]'), function(key, value) {
                                        $(value).remove();
                                    });
                                    // попробуем добавить option в выбиратор и создать параллельные поля
                                    $.each(items, function(key, value){
                                        var option = $("<option>");
                                        option.attr({
                                            "value": value.id
                                        }).text(value.name);
                                        $(selector).append(option);
                                        var inputId = $("<input>");
                                        inputId.attr({
                                            "type": "hidden",
                                            "name": "dialog[id][]",
                                            "value": value.id
                                        });
                                        $(parent).append(inputId);
                                        var inputType = $("<input>");
                                        inputType.attr({
                                            "type": "hidden",
                                            "name": "dialog[type][]",
                                            "value": value.type
                                        });
                                        $(parent).append(inputType);
                                        var inputName = $("<input>");
                                        inputName.attr({
                                            "type": "hidden",
                                            "name": "dialog[name][]",
                                            "value": value.name
                                        });
                                        $(parent).append(inputName);
                                    });
                                    $(this).dialog("close");
                                }
                            }
                        });
                        // сохраняем родительский диалог в свойстве parentDialog
                        $(groupDialog).dialog("option", {
                            parentDialog: dialog
                        });
                    });
                });
            });
        });
        // добавляем к выбиратору с метелкой удаление из параллельного списка
        var deleter = $("#" + fieldName + "_deleter");
        $(deleter).css("cursor", "pointer");
        $(deleter).bind("click", function(){
            var index = $("#" + fieldName + "_select option").index($("#" + fieldName + "_select option:selected"));
            if (index == -1) {
                return true;
            }
            // получаем ранее выбранные элементы
            var items = new Array();
            var arr_ids = new Array();
            var arr_names = new Array();
            var arr_types = new Array();
            // получаем выбранных на момент нажатия людей
            $.each($('input[name="' + fieldName + '[name][]"]'), function(key, value){
                arr_names[arr_names.length] = $(value).val();
            });
            $.each($('input[name="' + fieldName + '[id][]"]'), function(key, value){
                arr_ids[arr_ids.length] = $(value).val();
            });
            $.each($('input[name="' + fieldName + '[type][]"]'), function(key, value){
                arr_types[arr_types.length] = $(value).val();
            });
            $.each(arr_names, function(key, value) {
                var obj = new Object();
                obj.id = arr_ids[key];
                obj.name = arr_names[key];
                obj.type = arr_types[key];
                items[items.length] = obj;
            });
            // убираем указанный элемент из массива
            items.splice(index, 1);
            // убиваем поля документа
            var parent = $("#" + fieldName).parent();
            // удаляем параллельные поля диалога из документа
            $.each($('input[name="' + fieldName + '[type][]"]'), function(key, value) {
                $(value).remove();
            });
            $.each($('input[name="' + fieldName + '[id][]"]'), function(key, value) {
                $(value).remove();
            });
            $.each($('input[name="' + fieldName + '[name][]"]'), function(key, value) {
                $(value).remove();
            });
            var select = $("#" + fieldName + "_select");
            $(select).empty();
            // заполняем параллельные поля
            $.each(items, function(key, value) {
                var option = $("<option>");
                option.attr({
                    "value": value.id
                }).text(value.name);
                $(select).append(option);
                var inputId = $("<input>");
                inputId.attr({
                    "type": "hidden",
                    "name": fieldName + "[id][]",
                    "value": value.id
                });
                $(parent).append(inputId);
                var inputType = $("<input>");
                inputType.attr({
                    "type": "hidden",
                    "name": fieldName + "[type][]",
                    "value": value.type
                });
                $(parent).append(inputType);
                var inputName = $("<input>");
                inputName.attr({
                    "type": "hidden",
                    "name": fieldName + "[name][]",
                    "value": value.name
                });
                $(parent).append(inputName);
            });
        });

        // добавляем к полю для ввода автоподстановку
        $("#" + fieldName).autocomplete({
            source: web_root + "_modules/_acl_manager/?action=lookupNames",
            minLength: 2,
            select: function(event, ui) {

            }
        });
    }
})(jQuery);