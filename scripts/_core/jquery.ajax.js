/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 07.07.13
 * Time: 21:30
 * To change this template use File | Settings | File Templates.
 */
/**
 * Мой плагин для работы с AJAX повсеместно
 */
(function($){
    function asuAjax(params){
        this._doc = null;

        _initCenter = function(doc){
            /**
             * Делаем форму чуть красивее
             */
            var forms = jQuery(doc).find("form");
            for (var i = 0; i < forms.length; i++) {
                jQuery(forms[i]).on("submit", {
                    parentObj: this
                }, function(event){
                    event.data.parentObj._showOverlay();
                });
                /**
                 * Если это новая форма, а в ней есть
                 * ошибки, то подсвечиваем блоки с ошибками
                 */
                jQuery(forms[i]).find("div.control-group").filter(function(){
                    return jQuery("span.help-inline", this).length == 1;
                }).addClass("error");
            }
        };

        _updateControlBlock = function(){

        };

        _hideOverlay = function(){
            var overlay = this._getOverlay();
            jQuery(overlay).css("display", "none");
        };

        _showOverlay = function(){
            var overlay = this._getOverlay();
            jQuery(overlay).css("display", "block");
        };

        _getOverlay = function(){
            this._overlay = jQuery("#overlay")
            if (this._overlay.length == 0) {
                this._overlay = jQuery('<div id="overlay"></div>');
                this._overlay.appendTo(document.body)
            }
            return this._overlay;
        };

        __construct = function(doc){
            /**
             * Инициализируем ссылки в документе
             */
            this._initCenter(doc);
            this._hideOverlay();
        }(params);
    }

    $.fn.asuAjax = function(){
        var ajax = asuAjax(this[0]);
    }
}(jQuery));
/**
 * Основной код, которые запускает все преобразование
 */
jQuery(document).ready(function(){
    jQuery(document).asuAjax();
});