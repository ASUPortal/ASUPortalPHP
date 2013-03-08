jQuery(document).ready(function(){

});
function copyToAnotherYear() {
    // смотрим, какие показатели выбраны
    var checkboxes = jQuery("#dataTable").find("input[type=checkbox]:checked");
    if (checkboxes.length == 0) {
        alert("Не выбрано ни одного показателя для копирования");
        return false;
    }
    // показываем окошко для выбора целевого года
    jQuery("#targetYearDialog").dialog({
        resizable: false,
        height: 200,
        modal: true,
        buttons: {
            "Копировать": function() {
                var checkboxes = jQuery("#dataTable").find("input[type=checkbox]:checked");
                var year = jQuery("#target_year").val();
                var items = new Array();
                jQuery.each(checkboxes, function(key, input) {
                    items[items.length] = jQuery(input).val();
                });
                // отправляем запрос на сервер
                jQuery.getJSON(
                    web_root + "_modules/_json_service/index.php",
                    {
                        controller: "rating",
                        action: "copyIndexes",
                        year: year,
                        indexes: items
                    },
                    function() {
                        alert("Показатели скопированы");
                    }
                );
                jQuery(this).dialog("close");
            },
            "Отмена": function() {
                jQuery(this).dialog("close");
            }
        }
    });
}