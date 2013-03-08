/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 10:43
 * To change this template use File | Settings | File Templates.
 */

var progressor = null;

/**
 * Показ/скрытие списка типов сорудников для протокола
 */
function showPersonTypeSelector() {
    if (jQuery("#person_type_selector").css("display") == "none") {
        jQuery("#person_type_selector").css("display", "block");
    } else {
        jQuery("#person_type_selector").css("display", "none");
    }
};
function updatePersonListField(fieldId) {
    var field = jQuery("#" + fieldId);
    if (field.length == 0) {
        field = jQuery("[name='"+ fieldId +"']");
        if (field.length == 1) {
            field = field[0];
        }
    }
    if (field.length == 0) {
        field = jQuery("[name='"+ fieldId +"[]']");
        if (field.length == 1) {
            field = field[0];
        }
    }
    // выключаем поле
    jQuery(field).attr("disabled", "disabled");
    // получаем список поставленных галочек
    var types = new Array();
    var selectors = jQuery("#person_type_selector").children("span").children("input:checked");
    jQuery.each(selectors, function(index, value) {
        types[types.length] = jQuery(value).attr("value");
    });
    // запрашиваем сервер
    jQuery.ajax({
        url: web_root + "_modules/_json_service/index.php",
        type: "POST",
        dataType: "JSON",
        data: {
            controller: "staff",
            action: "getStaffWithRoles",
            roles: types.join(",")
        }
    }).done(function(persons) {
        jQuery(field).find("option").remove();
        jQuery.each(persons, function(key, value) {
            jQuery(field).append(jQuery("<option>").attr("value", key).text(value));
        });
        jQuery(field).removeAttr("disabled");
    });
}