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
});