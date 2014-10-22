/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.10.14
 * Time: 13:54
 * To change this template use File | Settings | File Templates.
 */
(function($){
    var Flow = {
        _params: {

        },

        _hideOverlay:  function(){
            var overlay = this._getOverlay();
            jQuery(overlay).css("display", "none");
        },

        _showOverlay: function(){
            var overlay = this._getOverlay();
            jQuery(overlay).css("display", "block");
        },

        _getOverlay: function(){
            this._overlay = jQuery("#overlay")
            if (this._overlay.length == 0) {
                this._overlay = jQuery('<div id="overlay"></div>');
                this._overlay.appendTo(document.body)
            }
            return this._overlay;
        },

        _isObject: function(data){
            var isObject = false;
            if (typeof data == "string") {
                if (data.substr(0, 1) == "{") {
                    isObject = true;
                }
            }
            return isObject;
        },

        _onOkButtonClick: function(){
            // this - кнопка
            var dialogContainer = jQuery("#flowDialogPlaceholder");
            var properties = {
                targetClass: "",
                targetMethod: "",
                target: "",
                flow: true
            };
            var items = jQuery("input", dialogContainer).serializeArray();
            for (var i = 0; i < items.length; i++) {
                var obj = items[i];
                properties[obj.name] = obj.value;
            }
            var items = jQuery("select", dialogContainer).serializeArray();
            for (var i = 0; i < items.length; i++) {
                var obj = items[i];
                properties[obj.name] = obj.value;
            }

            // закрываем диалог
            jQuery("#flowDialogPlaceholder>div>div").modal("hide");

            // если указан класс и метод, то будем отправлять запрос
            // иначе это может быть просто информационное окно
            if (properties.targetClass == "" || properties.targetMethod == "") {
                return false;
            }

            // показываем оверлей
            this._showOverlay();

            // отправляем запрос на сервер
            var ajax = jQuery.ajax({
                url: properties.target,
                cache: false,
                data: properties,
                success: jQuery.proxy(this._onAjaxRequestComplete, this)
            });
        },

        _onAjaxRequestComplete: function(data){
            // скрываем оверлей
            this._hideOverlay();
            // в зависимости от типа ответа, будем дальше обрабатывать результат
            if (!this._isObject(data)) {
                // пусть будет диалогом
                var dialog = jQuery("#flowDialogPlaceholder");
                if (dialog.length == 0) {
                    dialog = jQuery('<div id="flowDialogPlaceholder">');
                    dialog.appendTo(document.body);
                }
                // если нам пришел не готовый диалог, а просто текст
                // делаем его диалогом
                var html = jQuery.parseHTML(data);
                if (jQuery(".modal", html).length == 0) {
                    data = '<div><div class="modal">' + data + "</div></div>";
                }
                jQuery(dialog).html(data);
                jQuery("#flowDialogPlaceholder>div>div").modal();

                // на главную кнопку диалога вешаем продолжение флоу
                var nextButton = jQuery(".btn-primary", dialog);
                jQuery(nextButton).on("click", jQuery.proxy(this._onOkButtonClick, this));
            } else {
                this._showOverlay();
                var params = jQuery.parseJSON(data);
                if (params.action == "redirect") {
                    // редирект
                    window.location.href = params.url;
                    // скрыть оверлей
                    this._hideOverlay();
                } else if (params.action == "redirectNextAction") {
                    // передача управления следующему действию
                    Flow.init({
                        targetClass: params.targetClass,
                        targetMethod: params.targetMethod,
                        beanId: params.beanId
                    });
                    Flow.startNextAction();
                } else {
                    alert("Дейсвтие " + params.action + " еще не реализовано");
                }
            }
        },

        startFirstFromUrl: function(){
            // показываем оверлей
            this._showOverlay();
            var ajax = jQuery.ajax({
                cache: false,
                url: this._params.url,
                data: {
                    beanData: this._params.beanData
                },
                success: jQuery.proxy(this._onAjaxRequestComplete, this)
            });
        },

        startNextAction: function(){
            // показываем оверлей
            this._showOverlay();
            this._params["flow"] = true;
            var ajax = jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_flow/",
                data: this._params,
                success: jQuery.proxy(this._onAjaxRequestComplete, this)
            });
        },

        init: function(params){
            this._params = params;
        }
    };

    /**
     * Создадим Flow из ссылки
     * Для совместимости со всем старым кодом
     *
     * @param params
     * @constructor
     */
    function FlowFromLink(params) {
        this._link = 0;

        /**
         * Запускалка по щелчку мыши
         *
         * @returns {boolean}
         * @private
         */
        _onLinkClick = function(){
            var beanData = {};
            var properties = jQuery("[asu-type=flow-property]", this._link);
            jQuery.each(properties, function(key, value){
                if (jQuery(value).attr("value") == "selectedInView") {
                    var selectedItems = new Array();
                    jQuery.each(jQuery("input[name='selectedDoc']:checked"), function(key, value){
                        selectedItems.push(jQuery(value).val());
                    });
                    beanData[jQuery(value).attr("name")] = selectedItems.join(":");
                } else {
                    beanData[jQuery(value).attr("name")] = jQuery(value).attr("value");
                }
            });
            Flow.init({
                url: this._link.href,
                beanData: beanData
            });
            Flow.startFirstFromUrl();
            return false;
        };

        /**
         * Конструктор
         *
         * @type {__construct}
         * @private
         */
        __construct = function(params){
            this._link = params[0];
            jQuery(this._link).on("click", jQuery.proxy(this._onLinkClick,  this));
        }(params);
    };

    $.fn.ajaxFlow = function(){
        jQuery.each(this, function(key, value){
            if (jQuery(value).is("a")) {
                var ajaxFlow = FlowFromLink(jQuery(value));
            }
        });
    }
}(jQuery));

jQuery(document).ready(function(){
    jQuery("[asu-action=flow]").ajaxFlow();
});