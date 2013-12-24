/**
 * Created with JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.12.13
 * Time: 10:13
 * To change this template use File | Settings | File Templates.
 */
(function($){
    $.fn.catalogLookupWidget = function(){
        var _lookup = null;
        var _catalog = null;
        var _lookupItems = null;
        var _isMultiple = false;
        var _values = null;
        var _placeholder = null;

        // обработка пользовательского ввода
        this._onTypeAhead = function(query, process){
            var context = this.options.context;

            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "json",
                data: {
                    "action": "LookupTypeAhead",
                    "query": query,
                    "catalog": context._catalog
                },
                context: context,
                success: function(data){
                    this._lookupItems = data;
                    // показываем подсказку
                    var lookupItems = new Array();
                    jQuery.each(this._lookupItems, function(key, value){
                        lookupItems[lookupItems.length] = value;
                    });
                    process(lookupItems);
                }
            });
        };
        // обработка выбора в TypeAhead-е
        this._onTypeAheadSelect = function(item){
            // добавляем
            var id = null;
            jQuery.each(this.options.context._lookupItems, function(key, value){
                if (value == item) {
                    id = key;
                }
            });
            if (!this.options.context._isMultiple) {
                this.options.context._values = new Array();
            }
            this.options.context._values[this.options.context._values.length] = id;
            // обновляем представление
            this.options.context._updateDisplay();
        };

        // получить номер в массиве выбранного элемента
        this._getSelectedIndex = function() {
            var items = jQuery(".btn-inverse", this._placeholder);
            var result = 0;
            for (var i = 0; i < items.length; i++) {
                result = jQuery(items[i]).attr("index");
            }
            return result;
        }

        // обновление представления
        this._updateDisplay = function(){
            // убираем имеющееся
            jQuery(this._placeholder).empty();
            // создаем новые
            for (var i = 0; i < this._values.length; i++) {
                jQuery.ajax({
                    cache: false,
                    url: web_root + "_modules/_search/",
                    dataType: "json",
                    data: {
                        "action": "LookupGetItem",
                        "id": this._values[i],
                        "catalog": this._catalog
                    },
                    context: this,
                    success: function(data){
                        if (jQuery.isPlainObject(data)) {
                            var context = this;
                            jQuery.each(data, function(key, value){
                                var item = jQuery("<div/>", {
                                    "class": "btn",
                                    "text": value,
                                    "index": key
                                });
                                jQuery(item).attr("index", key);
                                jQuery(item).appendTo(context._placeholder);
                                jQuery(item).on("click", context._onSelectorClick);
                            });
                        }
                    }
                });
            }
            // удаляем старые поля ввода
            var itemName = jQuery(this).attr("asu-value-name");
            var items = jQuery("[asu-type=value]", this);
            jQuery.each(items, function(key, item){
                jQuery(item).remove();
            });
            // если нечего показывать, то добавляем ноль
            if (this._values.length == 0) {
                this._values[this._values.length] = 0;
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

        // обработка удаления
        this._onRemoveButtonClick = function(event){
            var context = event.data;
            var value = context._getSelectedIndex();
            if (value == 0) {
                return true;
            }
            context._values.splice(jQuery.inArray(value, context._values), 1);
            context._updateDisplay();
        };

        // обработка щелчка на элементе
        this._onSelectorClick = function(){
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

        this._init = function(){
            // получаем каталог
            this._catalog = jQuery(this).attr("asu-catalog");

            // множественный выбор
            this._isMultiple = (jQuery(this).attr("asu-multiple") == "true");

            // инициализируем исходные данные
            this._placeholder = jQuery("[asu-type=placeholder]", this);
            var items = jQuery("[asu-type=value]", this);
            this._values = new Array();
            for (var i = 0; i < items.length; i++) {
                this._values[this._values.length] = jQuery(items[i]).val();
            }
            this._updateDisplay();

            // инициализируем лукап
            this._lookup = jQuery("[asu-name=lookup]", this).typeahead({
                items: 10,
                minLength: 1,
                source: this._onTypeAhead,
                updater: this._onTypeAheadSelect,
                context: this
            });

            // обработчик кнопки удаления
            var removeButton = jQuery(".icon-remove", this);
            jQuery(removeButton).css("cursor", "pointer");
            jQuery(removeButton).on("click", this, this._onRemoveButtonClick);
        };

        this._init();
        return this;
    };
}(jQuery));
/**
 * Из всех выбранных элементов делаем lookup-виджеты
 */
(function($){
    $.fn.catalogLookup = function(){
        return this.each(function(){
            jQuery(this).catalogLookupWidget();
        });
    };
}(jQuery));