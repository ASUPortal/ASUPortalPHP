/**
 * Created by abarmin on 30.05.15.
 */
(function($){
    $.fn.widget = function(){
        var _controllerUrl = "";
        var _controllerAction = "";
        var _componentId = "";

        /**
         * Инициализация виджета
         *
         * @private
         */
        this._init = function(){
            /**
             * Получаем параметры по умолчанию
             */
            this._controllerUrl = jQuery(this).attr("asu-controller");
            this._controllerAction = jQuery(this).attr("asu-action");
            this._componentId = jQuery(this).attr("id");
            /**
             * Загрузим данные по умолчанию
             */
            this._loadData(this._controllerUrl, {
                action: this._controllerAction
            });
        };

        /**
         * Центральный метод для загрузки данных
         *
         * @param url
         * @param data
         * @private
         */
        this._loadData = function(url, data, isPost){
            var method = "GET";
            if (isPost) {
                method = "POST";
            }
            jQuery.ajax({
                url: url,
                data: data,
                cache: false,
                method: method,
                beforeSend: jQuery.proxy(this._onBeforeSend, this),
                success: jQuery.proxy(this._onDataLoaded, this),
                error: jQuery.proxy(this._onDataLoaded, this)
            });
        };

        /**
         * Данные получены, покажем их
         *
         * @private
         */
        this._onDataLoaded = function(loadedHtml){
        	var data = jQuery.parseHTML(loadedHtml);
            var that = this;
            /**
             * Удалим со все ссылок удаления их родные события
             * Это большой-большой костыль
             */
            jQuery("a.icon-trash", data).filter(function(){
                if (jQuery(this).prop("onclick")) {
                    return true;
                }
                return false;
            }).each(function(){
                /**
                 * Узнаем location.href родного события
                 */
                var text = this.outerHTML;
                var link = text.substring(
                    text.indexOf("location.href='") + "location.href='".length
                );
                link = link.substring(0, link.indexOf("'"));
                link = link.replace("&amp;", "&");
                /**
                 * Узнаем текст предупреждения
                 */
                var msg = text.substring(
                    text.indexOf("confirm('") + "confirm('".length
                );
                msg = msg.substring(msg, msg.indexOf("'"));
                /**
                 * Удалим обработчик щелчка мыши
                 */
                jQuery(this).prop("onclick", null);
                /**
                 * Добавим свой обработчик
                 */
                jQuery(this).on("click", function(){
                    if (confirm(msg)) {
                        var href = link.substring(0, link.indexOf("?"));
                        var paramArr = link.substring(link.indexOf("?") + 1).split("&");
                        var params = {};
                        jQuery.each(paramArr, function(index, object){
                            params[object.substring(0, object.indexOf("="))] =
                                object.substring(object.indexOf("=") + 1);
                        });
                        that._loadData(href, params);
                        return false;
                    }
                });
            });
            /**
             * Забиндимся на все ссылки кроме #_saveAndContinue и #_saveAndBack
             */
            jQuery("a", data).not("#_saveAndContinue, #_saveAndBack, .icon-trash").on("click", function(){
                var link = jQuery(this).attr("href");
                var href = link.substring(0, link.indexOf("?"));
                var paramArr = link.substring(link.indexOf("?") + 1).split("&");
                var params = {};
                jQuery.each(paramArr, function(index, object){
                    params[object.substring(0, object.indexOf("="))] =
                        object.substring(object.indexOf("=") + 1);
                });
                that._loadData(href, params);
                return false;
            });
            /**
             * Теперь забиндимся на все формы
             * Тут такой вот хак, так как формы напрямую не находятся
             */
            var buttons = jQuery("button", data);
            jQuery(buttons).each(function(index, button){
                var form = jQuery(button).closest('form');
                jQuery(form).on("submit", jQuery.proxy(that._formSubmit, that, form[0]));
            });
            jQuery("a#_saveAndContinue", data).on("click", function(){
                /**
                 * Ставим признак продолжения редактирования формы
                 */
                var form = jQuery(this).parents("form:first");
                jQuery("input[name=_continueEdit]", form).val("1");
                that._formSubmit(form);
                return false;
            });
            jQuery("a#_saveAndBack", data).on("click", function(){
                /**
                 * Снимаем признак продолжения редактирования формы
                 */
                var form = jQuery(this).parents("form:first");
                jQuery("input[name=_continueEdit]", form).val("0");
                that._formSubmit(form);
                return false;
            });
            jQuery(that).html(data);

	        var regexp = /<script>([\s\S]*?)<\/script>/gmi;
	
	        var match;
	        while (match = regexp.exec(loadedHtml)) {
	            var script = match[1];
	            jQuery.globalEval(script);
	        }
        };

        /**
         * Штатно сабмитим форму
         *
         * @private
         */
        this._formSubmit = function(form){
            /**
             * Соберем параметры формы
             */
            var paramsArr = jQuery(form).serializeArray();
            jQuery("button", form).each(function(index, button){
                if (jQuery(button).attr('name') && jQuery(button).attr('value')) {
                    paramsArr.push({
                        name: jQuery(button).attr('name'),
                        value: jQuery(button).attr('value')
                    });
                }
            });
            var params = {};
            jQuery.each(paramsArr, function(index, obj){
                params[obj.name] = obj.value;
            });
            /**
             * Теперь надо узнать, куда ее сабмитить
             */
            var href = jQuery(form).attr("action");
            this._loadData(href, params, true);
            return false;
        };

        /**
         * Перед загрузкой данных покажем загружатор
         *
         * @private
         */
        this._onBeforeSend = function(){
            jQuery(this).wrapInner('<div class="componentLoadingWrapper" />');
        };

        this._init();
        return this;
    }
}(jQuery));

(function($){
    $.fn.components = function(){
        return this.each(function(){
            jQuery(this).widget();
        });
    };
}(jQuery));