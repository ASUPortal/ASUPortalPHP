/**
 * Created by abarmin on 30.05.15.
 */
(function($){
    $.fn.componentWidget = function(){
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
                url: this._controllerUrl,
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
        this._onDataLoaded = function(data){
            /**
             * Забиндимся на все ссылки кроме #_saveAndContinue и #_saveAndBack
             * Сначала предотвратим щелчки на них
             */
            data = jQuery.parseHTML(data);
            var that = this;
            jQuery("a", data).not("#_saveAndContinue, #_saveAndBack").on("click", function(){
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
            var form = jQuery("button", data).closest("form");
            jQuery(form).on("submit", jQuery.proxy(this._formSubmit, this));
            jQuery("a#_saveAndContinue", data).on("click", function(){
                /**
                 * Ставим признак продолжения редактирования формы
                 */
                var form = jQuery(this).parents("form:first");
                jQuery("input[name=_continueEdit]", form).val("1");
                that._formSubmit();
                return false;
            });
            jQuery("a#_saveAndBack", data).on("click", function(){
                /**
                 * Снимаем признак продолжения редактирования формы
                 */
                var form = jQuery(this).parents("form:first");
                jQuery("input[name=_continueEdit]", form).val("0");
                that._formSubmit();
                return false;
            });
            /**
             * Покажем содержимое контейнера.
             * Чтобы не было мерцания сделаем задержку
             */
            setTimeout(function(){
                jQuery(that).html(data)
                    .find(".catalogLookup").catalogLookup().end()
                    .find("[asu-type='component']").components().end();
            }, 500);
        };

        /**
         * Штатно сабмитим форму
         *
         * @private
         */
        this._formSubmit = function(){
            /**
             * Соберем параметры формы
             */
            var form = jQuery("form", this);
            var paramsArr = jQuery(form).serializeArray();
            var params = {};
            jQuery.each(paramsArr, function(index, obj){
                params[obj.name] = obj.value;
            });
            /**
             * Теперь надо узнать, куда ее сабмитить
             */
            var href = jQuery("form").attr("action");
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
            jQuery(this).componentWidget();
        });
    };
}(jQuery));