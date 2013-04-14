/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.04.13
 * Time: 12:52
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function(){
    setInterval(checkForNewMessages, 5000);
});
/**
 * Проверка на наличие новых сообщений
 */
function checkForNewMessages() {
    /**
     * Смотрим на наличие в куках текущего числа сообщений
     * Если в куках этого параметра нет, то отправляем на сервер
     * запрос и показываем пользователю уведомление.
     */
    var fromCookies = jQuery.cookie("messages");
    if (fromCookies == null) {
        /**
         * В куках нет количества сообщений.
         * Пусть тогда их будет ноль
         */
        fromCookies = 0;
    }
    /**
     * Теперь обращаемся к серверу дабы узнать, сколько
     * там на самом деле непрочитанных сообщений
     */
    jQuery.ajax({
        url: web_root + "_modules/_mail/",
        method: "post",
        data: {
            action: "checkmail"
        },
        dataType: "json",
        method: "post"
    }).done(function(data){
        if (data.unread > parseInt(fromCookies)) {
            /**
             * Выдаем сообщение пользователю
             */
            jQuery.sticky("<div>" +
                "<a href='" + web_root + "_modules/_mail/'>" +
                "<img align='right' src='" + web_root + "images/tango/32x32/apps/internet-mail.png'>" +
                "У Вас новое сообщение" +
                "</a>" +
                "<div style='clear: both; '></div>" +
                "</div>", {
                "autoclose": false
            });
        }
        fromCookies = data.unread;
        /**
         * Устанавливаем значение обратно в куку
         */
        jQuery.cookie("messages", fromCookies);
    });
}