jQuery(document).ready(function(){
    jQuery("#autoFillLoadGo").on("click", function(){
        jQuery.ajax({
            url: web_root + "_modules/_individual_plan/work.php",
            data: jQuery("#fillLoadForm").serialize(),
            type: "POST",
            dataType: "json",
            beforeSend: function(){

            },
            success: function(data){
                // раскладываем полученные данные в стобцы с планом
                jQuery.each(data, function(isContract, works){
                    jQuery.each(works, function(workId, columns){
                        jQuery.each(columns, function(monthId, value){
                            var fieldName = "CModel[data][" + isContract + "][" + workId + "][" + monthId + "][0]";
                            jQuery("input[name='" + fieldName + "']").val(value);
                        });
                    });
                });
                // скрываем диалог
                jQuery("#autofill").modal("hide");
            }
        });
    });
});
function getWorkId(itemName) {
    var values = itemName.split("[");
    var value = values[3].substr(0, values[3].indexOf("]"))
    return value;
}
function autoPropogate(){
    // осенний семестр
    var items = jQuery("input[name^='CModel[data][0]'][name$='[20]'][0]");
    var months = new Array(9, 10, 11, 12, 1);
    // нагрузка по бюджету есть всегда
    jQuery.each(items, function(key, item){
        var workId = getWorkId(jQuery(item).attr("name"));
        var monthLoad = (jQuery(item).val() / months.length).toFixed(2);
        for (var i = 0; i < months.length; i++) {
            jQuery("input[name='CModel[data][0][" + workId + "][" + months[i] + "][0]']").val(monthLoad);
        }
    });
    // нагрузка по контракту
    var items = jQuery("input[name^='CModel[data][1]'][name$='[20]']");
    if (items.length > 0) {
        jQuery.each(items, function(key, item){
            var workId = getWorkId(jQuery(item).attr("name"));
            var monthLoad = (jQuery(item).val() / months.length).toFixed(2);
            for (var i = 0; i < months.length; i++) {
                jQuery("input[name='CModel[data][1][" + workId + "][" + months[i] + "][0]']").val(monthLoad);
            }
        });
    }
    // весенний семестр
    var items = jQuery("input[name^='CModel[data][0]'][name$='[21]']");
    var months = new Array(2, 3, 4, 5, 6, 7);
    // нагрузка по бюджету есть всегда
    jQuery.each(items, function(key, item){
        var workId = getWorkId(jQuery(item).attr("name"));
        var monthLoad = (jQuery(item).val() / months.length).toFixed(2);
        for (var i = 0; i < months.length; i++) {
            jQuery("input[name='CModel[data][0][" + workId + "][" + months[i] + "][0]']").val(monthLoad);
        }
    });
    // нагрузка по контракту
    var items = jQuery("input[name^='CModel[data][1]'][name$='[21]']");
    if (items.length > 0) {
        jQuery.each(items, function(key, item){
            var workId = getWorkId(jQuery(item).attr("name"));
            var monthLoad = (jQuery(item).val() / months.length).toFixed(2);
            for (var i = 0; i < months.length; i++) {
                jQuery("input[name='CModel[data][1][" + workId + "][" + months[i] + "][0]']").val(monthLoad);
            }
        });
    }
    return false;
}