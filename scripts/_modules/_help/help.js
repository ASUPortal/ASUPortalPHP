/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 18:01
 * To change this template use File | Settings | File Templates.
 */
/**
 * Инициализируем иконку личного помощника в правом верхнему углу экрана
 */
jQuery(document).ready(function() {
    // создаем помощника, цепляем его к телу страницы
    var helper = jQuery("<div>");
    jQuery(helper).addClass("asu_helper");
    jQuery("body").append(helper);
});