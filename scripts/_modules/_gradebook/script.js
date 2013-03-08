/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 20.12.12
 * Time: 22:01
 * To change this template use File | Settings | File Templates.
 */
/**
 * Заполнение полей формы для добавления записи из куков
 *
 * @param type
 */
function fillDataFromCookies(type) {
    if (type == "single") {
    	if (jQuery.cookie("gradebook[single][date_act]") !== null) {
            jQuery("#date_act").val(jQuery.cookie("gradebook[single][date_act]"));
            jQuery("#subject_id").val(jQuery.cookie("gradebook[single][subject_id]"));
            jQuery("#kadri_id").val(jQuery.cookie("gradebook[single][kadri_id]"));
            jQuery("#group_id").val(jQuery.cookie("gradebook[single][group_id]"));
            if (jQuery.cookie("gradebook[single][group_id]") !== "") {
                jQuery.getJSON(
                    web_root + "_modules/_json_service",
                    {
                        controller: "staff",
                        action: "getStudentsByGroup",
                        group: jQuery("#group_id").val()
                    },
                    function (students) {
                        jQuery("#student_id").empty();
                        jQuery.each(students, function (key, value) {
                            jQuery("#student_id").append('<option value="' + key + '">' + value + '</option>');
                        });
                        jQuery("#student_id").val(jQuery.cookie("gradebook[single][student_id]"));
                    }
                );
            }
            jQuery("#study_act_id").val(jQuery.cookie("gradebook[single][study_act_id]"));
            jQuery("#study_act_comment").val(jQuery.cookie("gradebook[single][study_act_comment]"));
            jQuery("#study_mark").val(jQuery.cookie("gradebook[single][study_mark]"));
            jQuery("#comment").val(jQuery.cookie("gradebook[single][comment]"));
            if (jQuery("#study_mark").val() !== 0) {
                jQuery("#study_mark").css("background", "yellow");
            } else {
                jQuery("#study_mark").css("background", "none");
            }
    	}
    } else if (type == "multiple") {
    	if (jQuery.cookie("gradebook[multiple][date_act]") !== null) {
    		jQuery("#date_act").val(jQuery.cookie("gradebook[multiple][date_act]"));
            jQuery("#subject_id").val(jQuery.cookie("gradebook[multiple][subject_id]"));
            jQuery("#kadri_id").val(jQuery.cookie("gradebook[multiple][kadri_id]"));
            jQuery("#group_id").val(jQuery.cookie("gradebook[multiple][group_id]"));
            if (jQuery.cookie("gradebook[single][group_id]") !== "") {
                jQuery.getJSON(
                    web_root + "_modules/_json_service",
                    {
                        controller: "staff",
                        action: "getStudentsByGroup",
                        group: jQuery("#group_id").val()
                    },
                    function (students) {
                        jQuery("#student_container").empty();
                        jQuery.each(students, function (key, value) {
                            var item = jQuery(jQuery("#student_template").find("p")[0]).clone().appendTo("#student_container");

                            var label = jQuery(item).find("label")[0];
                            jQuery(label).html(value);
                            jQuery(label).attr("for", jQuery(label).attr("for") + "[" + key + "]");

                            var select = jQuery(item).find("select")[0];
                            jQuery(select).attr("name", jQuery(select).attr("name") + "[" + key + "]");
                            jQuery(select).attr("id", "student_" + key);

                            if (jQuery.cookie("gradebook[multiple][student_" + key + "]") !== null) {
                                jQuery("#student_" + key).val(jQuery.cookie("gradebook[multiple][student_" + key + "]"));
                                jQuery("#student_" + key).css("background", "yellow");
                            }
                        });
                    }
                );
            }
            jQuery("#study_act_id").val(jQuery.cookie("gradebook[multiple][study_act_id]"));
            jQuery("#study_act_comment").val(jQuery.cookie("gradebook[multiple][study_act_comment]"));
            jQuery("#study_mark").val(jQuery.cookie("gradebook[multiple][study_mark]"));
            jQuery("#comment").val(jQuery.cookie("gradebook[multiple][comment]"));
    	}
    }
}
/**
 * Очистка оценки
 *
 * @param type
 */
function clearMarks(type) {
    if (type == "single") {
        jQuery("#study_mark").val("0");
        jQuery("#study_mark").css("background", "none");
    } else if (type == "multiple") {
        var items = jQuery("#student_container").find("select");
        jQuery.each(items, function(key, value) {
            jQuery(value).val("0");
            jQuery(value).css("background", "none");
        });
    }
}
/**
 * Очистка всех полей
 * 
 * @param type
 */
function clearAll(type) {
	var items = new Array();
	items[items.length] = "#subject_id";
	items[items.length] = "#kadri_id";
	items[items.length] = "#group_id";
	items[items.length] = "#study_act_id";
	if (type == "single") {
		items[items.length] = "#student_id";
		items[items.length] = "#study_mark";
		jQuery("#student_id").empty();
		jQuery("#study_mark").css("background", "none");
	} else if (type == "multiple") {
        var selects = jQuery("#student_container").find("select");
        jQuery.each(selects, function(key, value) {
            jQuery(value).val("0");
            jQuery(value).css("background", "none");
        });		
	}
	jQuery.each(items, function(key, value) {
		jQuery(value).val("0");
	});	
	jQuery("#date_act").val("");
	jQuery("#study_act_comment").val("");
	jQuery("#comment").val("");	
}