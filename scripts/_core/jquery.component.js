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
        this._loadData = function(url, data){
            jQuery.ajax({
                url: this._controllerUrl,
                data: data,
                cache: false,
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
             * Забиндимся на все ссылки
             * Сначала предотвратим щелчки на них
             */
            data = jQuery.parseHTML(data);
            var that = this;
            jQuery("a", data).on("click", function(){
                var href = jQuery(this).attr("href");


                that._loadData(jQuery(this).attr("href"), {});
                return false;
            });
            jQuery(this).html(data);
        };

        /**
         * Перед загрузкой данных покажем загружатор
         *
         * @private
         */
        this._onBeforeSend = function(){

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