/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 13:27
 * To change this template use File | Settings | File Templates.
 */
/**
 * Показ/скрытие списка типов сорудников для протокола
 */
function protocols_showPersonTypeSelector() {
    if (jQuery("#personTypeSelector").css("display") == "none") {
        jQuery("#personTypeSelector").css("display", "block");
    } else {
        jQuery("#personTypeSelector").css("display", "none");
    }
};
/**
 * Дозагрузка списка преподавателей в зависимости от выбранных типов
 */
function protocols_updatePersonListByType() {
    /**
     * Получаем перечень поставленных галочек
     */
    var types = new Array();
    var selectors = jQuery("#personTypeSelector").children("p").children("input:checked");
    jQuery.each(selectors, function(index, value) {
        types[types.length] = jQuery(value).attr("value");
    });
    /**
     * Удаляем все строки из таблицы
     */
    var rows = jQuery("#personTable").children("tbody").children("tr");
    jQuery.each(rows, function(index, value) {
        if (!jQuery(value).hasClass("header")) {
            jQuery(value).remove();
        }
    });
    var rows = jQuery("#tablePersonUncompulsory").children("tbody").children("tr");
    jQuery.each(rows, function(index, value) {
        if (!jQuery(value).hasClass("header")) {
            jQuery(value).remove();
        }
    });
    /**
     * Включаем прогрессор
     */
    var tr = jQuery("<tr></tr>");
    var td = jQuery('<td colspan="7" class="progressor" align="center"><img src="images/design/load.gif" /></td>');
    tr.append(td);
    jQuery("#personTable").children("tbody").append(tr);
    /**
     * Отправляем запрос на сервер для обновления данных в таблице
     */
    jQuery.ajax({
        url: "_modules/_json_service/index.php",
        type: "POST",
        dataType: "JSON",
        data: {
            controller: "staff",
            action: "getStaffWithRolesForProtocol",
            roles: types.join(",")
        }
    }).done(function(persons) {
        /**
         * Выключаем прогрессор, добавляем людей в список
         */
        var rows = jQuery("#personTable").children("tbody").children("tr");
        jQuery.each(rows, function(index, value) {
            if (!jQuery(value).hasClass("header")) {
                jQuery(value).remove();
            }
        });
        jQuery.each(persons, function(key, val) {
            if (key == "compulsory") {
                var i = 0;
                jQuery.each(val, function(index, value) {
                    i++;
                    var str = jQuery('' +
                        '<tr height=30>' +
                        '<td>' + i + '</td>' +
                        '<td>' +
                        '<div id="person_' + index + '" name="person_' + index + '" style="color:red;"><b>' + value + '</b></div>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="none" title="пропустить" onClick=javascript:hide_show_matter("' + index + '","hide",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="1" title="был" onClick=javascript:hide_show_matter("' + index + '","hide",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="0" title="не был" checked onClick=javascript:hide_show_matter("' + index + '","view",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=text id="table_kadri_off_matter_item_' + index + '" name="table_kadri_off_matter_item_' + index + '" style="display:block; width:190;" value="">' +
                        '</td>' +
                        '<td>&nbsp;</td>' +
                        '</tr>');
                    jQuery("#personTable").children("tbody").append(str);
                });
            } else if (key = "uncompulsory") {
                var i = 0;
                jQuery.each(val, function(index, value) {
                    i++;
                    var str = jQuery('' +
                        '<tr height=30>' +
                        '<td>' + i + '</td>' +
                        '<td>' +
                        '<div id="person_' + index + '" name="person_' + index + '" style="color:grey;"><b>' + value + '</b></div>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="none" title="пропустить" checked onClick=javascript:hide_show_matter("' + index + '","hide",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="1" title="был" onClick=javascript:hide_show_matter("' + index + '","hide",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=radio name="radio_item_' + index + '" value="0" title="не был" onClick=javascript:hide_show_matter("' + index + '","view",this.value)>' +
                        '</td>' +
                        '<td>' +
                        '<input type=text id="table_kadri_off_matter_item_' + index + '" name="table_kadri_off_matter_item_' + index + '" style="display:none; width:190;" value="">' +
                        '</td>' +
                        '<td>&nbsp;</td>' +
                        '</tr>');
                    jQuery("#tablePersonUncompulsory").children("tbody").append(str);
                });
                jQuery("#uncompulsoryPersonsCount").html(i);
            }
        });
    });
}