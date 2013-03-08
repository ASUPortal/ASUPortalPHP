function person_filter() {
    if (jQuery("#person_filter").css("display") == "none") {
        jQuery("#person_filter").css("display", "block");
    } else {
        jQuery("#person_filter").css("display", "none");
    }
}