/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.01.14
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */
(function($){
    $.fn.activeUploadWidget = function(){
        var _storage = null;
        var _isMultiple = null;
        var _imageSize = null;
        var _placeholder = null;
        var _values = null;
        var _upload = null;
        var _uploadForm = null;
        var _uploadFieldName = null;
        var _loadingPlaceholder = null;

        /**
         * Создание плашки просмотра
         *
         * @param data
         * @private
         */
        this._onItemLoaded = function(data){
            var container = jQuery("<div />", {
                "class": "btn",
                "value": data.name
            });
            var link = jQuery("<a />", {
                "href": data.fullUrl,
                "target": "_blank"
            });
            var image = jQuery("<img />", {
                "src": data.previewUrl
            });
            jQuery(container).append(link);
            jQuery(link).append(image);
            jQuery(this._placeholder).append(container);

            jQuery(container).on("click", this._onItemClick);
        };
        /**
         * Выбор элемента
         *
         * @private
         */
        this._onItemClick = function(){
            var needSelect = false;
            if (!jQuery(this).hasClass("btn-inverse")) {
                needSelect = true;
            }
            // снимаем выделение со всех
            var parent = jQuery(this).parent();
            jQuery(".btn", parent).each(function(key, button){
                if (jQuery(button).hasClass("btn-inverse")) {
                    jQuery(button).removeClass("btn-inverse");
                }
            });
            // ставим выделение, если надо
            if (needSelect) {
                jQuery(this).addClass("btn-inverse");
            }
        };
        /**
         * Номер выбранной плашки в массиве
         *
         * @returns {string}
         * @private
         */
        this._getSelectedIndex = function() {
            var items = jQuery(".btn-inverse", this._placeholder);
            var result = 0;
            for (var i = 0; i < items.length; i++) {
                result = jQuery(items[i]).attr("index");
            }
            return result;
        }
        this._onRemoveButtonClick = function(event){
            var context = event.data;
            var value = context._getSelectedIndex();
            if (value == 0) {
                return true;
            }
            context._values.splice(jQuery.inArray(value, context._values), 1);
            context._updateDisplay();
        };
        /**
         * Обновление представления
         *
         * @private
         */
        this._updateDisplay = function(){
            // удаляем все
            jQuery(this._placeholder).empty();
            jQuery(this._loadingPlaceholder).empty();
            for (var i = 0; i < this._values.length; i++) {
                if (this._values[i] != "") {
                    jQuery.ajax({
                        url: web_root + "_modules/_upload/",
                        cache: false,
                        type: "post",
                        dataType: "json",
                        context: this,
                        data: {
                            action: "getInfo",
                            _storage: this._storage,
                            _file: this._values[i],
                            _size: this._imageSize,
                            _index: i
                        },
                        success: this._onItemLoaded
                    });
                }
            }
            // удаляем старые поля ввода
            var itemName = jQuery(this).attr("asu-value-name");
            var items = jQuery("[asu-type=value]", this);
            jQuery.each(items, function(key, item){
                jQuery(item).remove();
            });
            // если нечего показывать, то добавляем пустое значение
            // (чтобы старое перетерлось)
            if (this._values.length == 0) {
                this._values[this._values.length] = "";
            }
            // создаем новые
            var itemHolder = this;
            jQuery.each(this._values, function(index, value){
                var input = jQuery("<input/>", {
                    "type": "hidden",
                    "name": itemName,
                    "value": value
                });
                jQuery(input).attr("asu-type", "value");
                jQuery(input).appendTo(itemHolder);
            });
        };
        /**
         * Обработка выбора файла
         *
         * @private
         */
        this._onFileSelected = function(event){
            if (jQuery(this).val() != "") {
                var parent = event.data;
                var form = parent._uploadForm;
                jQuery(form).ajaxSubmit({
                    url: web_root + "_modules/_upload/",
                    beforeSubmit: parent._onBeforeUpload,
                    success: parent._onUploadCompleted,
                    data: {
                        action: "uploadFile",
                        _storage: parent._storage,
                        _watch: parent._uploadFieldName
                    },
                    parent: parent
                });
            }
        };
        /**
         * После получения ответа от сервера
         *
         * @param data
         * @private
         */
        this._onUploadCompleted = function(data){
            var parent = this.parent;
            // включаем загружалку
            jQuery(parent._upload).removeAttr("disabled");
            jQuery(parent._upload).val("");
            // добавляем в список значений
            if (!parent._isMultiple) {
                parent._values = new Array();
            }
            parent._values[parent._values.length] = data;
            // обновляем компонент
            parent._updateDisplay();
        };
        /**
         * Перед отправкой файла на сервер
         *
         * @param data
         * @param form
         * @param options
         * @private
         */
        this._onBeforeUpload = function(data, form, options){
            // показываем прогрессор в плейсхолдере
            var parent = options.parent;
            parent._loadingPlaceholder = jQuery("<div />", {
                "class": "btn"
            });
            var image = jQuery("<img />", {
                "src": web_root + "images/ajax-loader.gif"
            });
            jQuery(parent._loadingPlaceholder).append(image);
            jQuery(parent._placeholder).append(parent._loadingPlaceholder);
            // выключаем загружалку
            jQuery(parent._upload).attr("disabled", "disabled");
        };

        this._init = function(){
            // папка, из которой брать и загружать файлы
            this._storage = jQuery(this).attr("asu-storage");
            // множественный выбор
            this._isMultiple = (jQuery(this).attr("asu-multiple") == "true");
            // размер изображения
            this._imageSize = jQuery(this).attr("asu-size");
            // загружалка, сразу вешаем событие
            this._upload = jQuery("[asu-type=upload]", this);
            // имя поля в форме, которое мониторить
            this._uploadFieldName = jQuery(this._upload).attr("name");
            this._uploadForm = jQuery(this).parents("form");
            jQuery(this._upload).on("change", null, this, this._onFileSelected);

            // удалялка
            var remover = jQuery(".icon-remove", this);
            jQuery(remover).css("cursor", "pointer");
            jQuery(remover).on("click", this, this._onRemoveButtonClick);

            // область для загрузки превьюшек
            this._placeholder = jQuery("[asu-type=placeholder]", this);
            // значения
            this._values = new Array();
            var items = jQuery("[asu-type=value]", this);
            for (var i = 0; i < items.length; i++) {
                this._values[this._values.length] = jQuery(items[i]).val();
            }
            // обновляем представление по имеющимся данным
            this._updateDisplay();
        };

        this._init();
        return this;
    };

    $.fn.activeUpload = function(){
        return this.each(function(){
            jQuery(this).activeUploadWidget();
        });
    }
}(jQuery));