/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 08.10.12
 * Time: 20:44
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function(){
    // показ-скрытие левого меню
    jQuery("#asu_menu_hider").click(function(){
        if (jQuery("#asu_body_menu").hasClass("span2")) {
            jQuery("#asu_body_menu").removeClass("span2").addClass("span1");
            jQuery("#asu_body_content").removeClass("span10").addClass("span11");
            jQuery.cookie("leftMainCol", "none");
        } else {
            jQuery("#asu_body_menu").removeClass("span1").addClass("span2");
            jQuery("#asu_body_content").removeClass("span11").addClass("span10");
            jQuery.cookie("leftMainCol", "");
        }
    });
    // автоматическое скрытие при открытии страницы
    if (jQuery.cookie("leftMainCol") == "none") {
        jQuery("#asu_body_menu").removeClass("span2").addClass("span1");
        jQuery("#asu_body_content").removeClass("span10").addClass("span11");
    }
    // переход на wap-версию
    jQuery("#asu_wap_switch").click(function(){
        jQuery.cookie("wap_mode", "true");
        // скрываем элементы управления
        jQuery("#asu_left_menu").hide("slow");
        jQuery("#asu_center_container").addClass("center_wap");
        jQuery("#asu_menu_hider").hide("slow");
        jQuery(".asu_header_asu_logo").css("background-image", "none");
        jQuery(".asu_header_ugatu_logo").css("background-image", "none");
        // добавление пунктов меню
        jQuery("#wap_main_menu").show("slow");
    });
    // переход по ссылкам wap-меню
    jQuery("select[name='wap_menu_list']").change(function(){
        location.href = jQuery(this).val();
    });
});