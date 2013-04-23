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
            jQuery("#birthdaysDialog").dialog("open");
        } else {
            /**
             * Загружаем диалог заново
             */
            jQuery('<div id="birthdaysDialog">').dialog({
                modal: true,
                title: "Ближайшие дни рождения",
                open: function(){
                    jQuery(this).load(web_root + "_modules/_dashboard/?action=showBirthdayDialog");
                }
            });
        }
        return false;
    });
});