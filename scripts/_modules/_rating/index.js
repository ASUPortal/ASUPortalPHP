var new_total = 0;
function addIndexValue() {
    new_total++;
    var table = jQuery("#index_values_table tr:first").after("<tr>" +
        "<td></td>" +
        "<td>" +
            "<input type=\"text\" name=\"CRatingValueForm[new_"+ new_total +"][title]\" style=\"width: 90%; \">" +
        "</td>" +
        "<td>" +
            "<input type=\"text\" name=\"CRatingValueForm[new_"+ new_total +"][value]\">" +
        "</td>" +
        "<td align=\"center\">" +
        "</td>" +
        "</tr>" +
        "<tr id=\"index_new_"+ new_total +"\">" +
        "<td>" +
            "<select name=\"CRatingValueForm[new_"+ new_total +"][evaluate_method]\" >" +
                "<option selected value=\"1\">SQL-запрос</option>" +
                "<option value=\"2\">PHP код</option>" +
            "</select>" +
        "</td>" +
        "<td>" +
            "<textarea name=\"CRatingValueForm[new_"+ new_total +"][evaluate_code]\" style=\"width: 100%; height: 250px; \"></textarea>" +
        "</td>" +
        "<td>" +
        "</td>" +
        "</tr>");
}
function showSystemProperties() {
    if (jQuery("#system_properties").css("display") == "none") {
        jQuery("#system_properties").css("display", "block");
    } else {
        jQuery("#system_properties").css("display", "none");
    }
}