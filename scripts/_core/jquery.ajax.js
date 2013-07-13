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
             * Делаем ajax-овые переходы по ссылкам
             */
            var links = jQuery(doc).find("div.asu_center_container").find("a");
            for (var i = 0; i < links.length; i++) {
                jQuery(links[i]).on("click", {
                    parentObj: this
                }, function(event){
                    event.data.parentObj._showOverlay();
                    jQuery.ajax(this.href, {
                        dataType: "html",
                        complete: function(){

                        },
                        success: function(data, status, xhr){
                            this.parentObj._linkAjaxSuccess(data, this.url);
                        },
                        parentObj: event.data.parentObj
                    });
                    return false;
                });
            }
            /**
             * Делаем ajax-овые формы
             */
             /*
            var forms = jQuery(doc).find("form");
            for (var i = 0; i < forms.length; i++) {
                jQuery(forms[i]).ajaxForm({
                    context: {
                        parentObj: this
                    },
                    dataType: "text",
                    beforeSubmit: function(){
                        this.context.parentObj._showOverlay();
                    },
                    success: function(data, status, xhr){
                        if (data.substring(0, 1) == "{") {
                            var url = jQuery.parseJSON(data);
                            url = url.url;
                            jQuery.ajax(url, {
                                dataType: "html",
                                complete: function(){

                                },
                                success: function(data, status, xhr){
                                    this.parentObj._linkAjaxSuccess(data, url);
                                },
                                parentObj: this.parentObj
                            });
                        } else {
                            this.parentObj._linkAjaxSuccess(data, window.location);
                        }
                    }
                });
            }
            */
        };

        /**
         * После выполнения запроса
         * @private
         */
        _linkAjaxComplete = function() {
            this._hideOverlay();
        };

        _getHeadScripts = function(doc){
            var scripts = new Array();
            for (var i = 0; i < jQuery(doc).length; i++) {
                var item = jQuery(doc)[i];
                var attr = jQuery(item).prop("nodeName");
                if (typeof attr !== 'undefined' && attr !== false) {
                    if (jQuery(item).prop("nodeName").toLowerCase() == "script") {
                        if (jQuery(item).prop("src") != "") {
                            scripts[scripts.length] = jQuery(item).prop("src");
                        }
                    }
                }
            }
            return scripts;
        };

        _getHeadStyles = function(doc){
            var scripts = new Array();
            for (var i = 0; i < jQuery(doc).length; i++) {
                var item = jQuery(doc)[i];
                var attr = jQuery(item).prop("nodeName");
                if (typeof attr !== 'undefined' && attr !== false) {
                    if (jQuery(item).prop("nodeName").toLowerCase() == "link") {
                        if (jQuery(item).prop("href") != "") {
                            scripts[scripts.length] = jQuery(item).prop("href");
                        }
                    }
                }
            }
            return scripts;
        };

        _loadPageScripts = function(doc, linkUrl){
            var loadDeferred = jQuery.Deferred();
            var headScripts = this._getHeadScripts(doc);
            var loadedScripts = new Array();
            for (var i = 0; i < headScripts.length; i++) {
                jQuery.ajax({
                    url: headScripts[i],
                    cache: false,
                    dataType: "script",
                    context: {
                        deferred: loadDeferred,
                        loaded: loadedScripts,
                        queue: headScripts,
                        parentObj: this,
                        parentDoc: doc,
                        parentUrl: linkUrl
                    }
                }).done(function(data, status, xhr){
                    this.loaded[this.loaded.length] = 1;
                    if (this.loaded.length == this.queue.length) {
                        this.deferred.resolve({
                            data: this.parentDoc,
                            parentObj: this.parentObj,
                            parentUrl: this.parentUrl
                        });
                    }
                });
            }
            return loadDeferred.promise();
        };

        _loadPageStyles = function(doc, parentUrl){
            var headStyles = this._getHeadStyles(doc);
            /**
             * Убиваем старые стили
             */
            var oldStyles = jQuery(document).find("head").find("link");
            for (var i = 0; i < oldStyles.length; i++) {
                var style = oldStyles[i];
                jQuery(style).remove();
            };
            /**
             * Грузим новые
             */
            for (var i = 0; i < headStyles.length; i++) {
                var style = jQuery("<link>");
                style.attr({
                    rel: "stylesheet",
                    type: "text/css",
                    href: headStyles[i]
                });
                jQuery("head").append(style);
            }
        };

        /**
         * Загрузка содержимого
         * @private
         */
        _linkAjaxSuccess = function(doc, url){
            var body = jQuery(doc).find("div.asu_center");
            /**
             * Если это не страница с новым дизайном, то просто редирект
             */
            if (body.length == 0) {
                window.location.href = url;
                return true
            }
            /**
             * Последовательно загружаем систему через Deferred
             * 1. Скрипты из шапки
             */
            var headScriptDeferred = this._loadPageScripts(doc, url);
            headScriptDeferred.done(function(params){
                params.parentObj._loadPageStyles(params.data, params.parentUrl);
                params.parentObj._replacePageContent(params.data);
                params.parentObj._inlineScriptsPlace(params.data);
                params.parentObj._initCenter(jQuery(document));
                params.parentObj._setPageUrl(params.parentUrl);
                params.parentObj._hideOverlay();
            });
        };

        _setPageUrl = function(url){
            window.history.pushState("data", "title", url);
            window.history.replaceState("data", "title", url);
        };

        _inlineScriptsPlace = function(doc){
            /**
             * Это старая модель, тоже можно переписать
             */
            var scripts = jQuery("script", doc);
            for (var i = 0; i < scripts.length; i++) {
                var script = scripts[i];
                var container = jQuery("<script />");
                jQuery(container).html(jQuery(script).html());
                jQuery("div.asu_center").append(container);
            }
        };

        _replacePageContent = function(doc){
            /**
             * Заменяем центральную часть страницы
             */
            var oldDoc = jQuery("div.asu_center");
            jQuery(oldDoc).empty();
            jQuery(oldDoc).html(jQuery(doc).find("div.asu_center").html());
            /**
             * Замена пунктов меню справа
             */
            var oldRight = jQuery("div.asu_right");
            jQuery(oldRight).replaceWith(jQuery(doc).find("div.asu_right"));
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
        }(params);
    }

    $.fn.asuAjax = function(){
        var ajax = new asuAjax(this[0]);
    }
}(jQuery));
/**
 * Основной код, которые запускает все преобразование
 */
jQuery(document).ready(function(){
    jQuery(document).asuAjax();
});