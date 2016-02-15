/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 22.04.13
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function(){
    jQuery(".item-birthdays a").on("click", function(event){
        /**
         * Показываем или загружаем диалог с днями
         * рождения
         */
        var dialog = jQuery("#birthdaysDialog");
        if (dialog.length > 0) {
            /**
             * Диалог уже есть, просто показываем его
             */
            jQuery("#birthdaysDialog>div").modal();
        } else {
            jQuery.ajax({
                url: web_root + "_modules/_dashboard/?action=showBirthdayDialog",
                cache: false,
                type: "get",
                beforeSend: function(){
                    jQuery("#overlay").css("display", "block");
                },
                success: function(data){
                    jQuery("#overlay").css("display", "none");
                    var container = jQuery('<div id="birthdaysDialog">').html(data);
                    jQuery("body").append(container);
                    jQuery("#birthdaysDialog>div").modal();
                }
            });
        }
        return false;
    });

    jQuery(".dashboard_report .item_hover").on("click", function(event){
        // увеличиваем размер блока
        // ставим туда загружалку АСУ
        var parent = jQuery(this).parents().first(".dashboard_report");
        jQuery(parent).html('<div style="opacity: 0.5; background: url(' + web_root +'images/loader.gif) center center no-repeat; height: 100%; width: 100%; "></div>');
        jQuery(parent).animate({
            "width": "600px"
        }, "slow", "swing", function(){
            jQuery.ajax({
                url: web_root + "_modules/_reports/?action=renderParams&id=" + jQuery(parent).attr("report_id"),
                cache: false,
                type: "get",
                success: function(data){
                    jQuery(parent).css("min-height", "300px");
                    jQuery(parent).html(data);
                    postParamsRendered(parent);
                }
            });
        });
    });
    /**
     * Событие после загрузки содержимого формы с параметрами отчета
     *
     * @param placeholder
     */
    function postParamsRendered(placeholder) {
        var sendButton = jQuery("[type=submit]", placeholder).on("click", function(){
            onReportSendToExecution(placeholder);
            return false;
        });
    }

    /**
     * Событие отправки отчета на исполнение
     *
     * @param placeholder
     */
    function onReportSendToExecution(placeholder) {
        // получаем форму, серилизуем ее
        var form = jQuery("form", placeholder).first();
        // показываем загружатор
        jQuery(placeholder).html('<div style="opacity: 0.5; background: url(' + web_root +'images/loader.gif) center center no-repeat; height: 100%; width: 100%; "></div>');
        jQuery.ajax({
            url: web_root + "_modules/_reports/?action=renderReport&id=" + jQuery(placeholder).attr("report_id"),
            cache: false,
            data: jQuery(form).serializeArray(),
            type: "get",
            success: function(data){
                jQuery(placeholder).html(data);
            }
        });
    }
});