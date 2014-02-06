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
		var _properties = null;

        // обработка пользовательского ввода
        this._onTypeAhead = function(query, process){
            var context = this.options.context;
			
			xhrData = context._properties;
			xhrData["action"] = "LookupTypeAhead";
			xhrData["query"] = query;
			xhrData["catalog"] = context._catalog;

            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "json",
                data: xhrData,
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

        // выбор в виде списка
        this._onGlassButtonClick = function(event){
            // показываем диалог, загружаем туда данные из каталога
            var parentObj = event.data;
			
            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "html",
                data: {
					action: "LookupGetDialog"
				},
                context: parentObj,
                success: function(data){
                    var lookupDialog = jQuery(data);
                    jQuery(lookupDialog).on("show", this, this._onDialogShow);
                    jQuery(lookupDialog).on("shown", this, this._onDialogShown);
                    jQuery("[asu-action=ok]", lookupDialog).on("click", this, this._onDialogOkClick);
                    jQuery(lookupDialog).modal();
                }
            });
        };

        // при показе диалога
        this._onDialogShow = function(event){
            // ставим загрузку в диалоге
            var place = jQuery(".modal-body", this);
            jQuery(place).html('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>');
        };

        // щелчок на элементе в диалоге
        this._onDialogRowClick = function(event){
            if (!event.data._isMultiple) {
                // выбор не множественный снимаем все остальные выбиралки
                var selected = jQuery(this).parents("table").find("input:checked");
                jQuery.each(selected, function(){
                    jQuery(this).attr("checked", false);
                });
            }
            // выбираем чекбокс в текущей строке
            var checkbox = jQuery(this).find("input[type=checkbox]");
            jQuery(checkbox).attr("checked", true);
        };

        // ок в диалоге
        this._onDialogOkClick = function(event){
            var dialog = jQuery(this).parents(".modal");
            // получаем выбранные элементы
            var items = jQuery("input:checked", dialog);
            // добавляем их в выбиратор
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                event.data._addNewValue(jQuery(item).val());
            }
            // обновляем представление
            event.data._updateDisplay();
            // закрываем диалог
            jQuery(dialog).modal("hide");
        };

        // загрузка данных в диалог
        this._onDialogShown = function(event){
            this.parentObject = event.data;
			var xhrData = this.parentObject._properties;
			xhrData["action"] = "LookupViewData";
			xhrData["catalog"] = this.parentObject._catalog;
					
            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "json",
                data: xhrData,
                context: this,
                success: function(data){
                    var parentObject = this.parentObject;
                    // загружаем полученные данные
                    var container = jQuery(".modal-body", this);
                    jQuery(container).empty();
                    var table = jQuery("<table/>",{
                        "class": "table table-hover table-condensed"
                    });
                    var body = jQuery("<tbody/>");
                    // для каждой полученной записи добавляем строку
                    var index = 1;
                    jQuery.each(data, function(key, value){
                        var row = jQuery("<tr/>");
                        jQuery(row).css("cursor", "pointer");
                        jQuery(row).on("click", parentObject, parentObject._onDialogRowClick);
                        jQuery(row).appendTo(body);

                        var selectColumn = jQuery("<td/>");
                        var selector = jQuery("<input/>", {
                            "type": "checkbox",
                            "value": key
                        });
                        jQuery(selector).appendTo(selectColumn);

                        var number = jQuery("<td/>", {
                            "text": index++
                        });
                        var dataRow = jQuery("<td/>", {
                            "text": value
                        });

                        jQuery(selectColumn).appendTo(row);
                        jQuery(number).appendTo(row);
                        jQuery(dataRow).appendTo(row);

                        jQuery(row).appendTo(body);
                    });
                    jQuery(body).appendTo(table);
                    jQuery(table).appendTo(container);
                }
            });
        };

        // добавление в список значений
        this._addNewValue = function(value){
            if (!this._isMultiple) {
                this._values = new Array();
            }
            this._values[this._values.length] = value;
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
            this.options.context._addNewValue(id);
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
				xhrData = this._properties;
				xhrData["action"] = "LookupGetItem";
				xhrData["id"] = this._values[i];
				xhrData["catalog"] = this._catalog;
			
                jQuery.ajax({
                    cache: false,
                    url: web_root + "_modules/_search/",
                    dataType: "json",
                    data: xhrData,
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
			
			// свойства
			this._properties = new Object();
			var properties = jQuery("[asu-type=property]", this);
			for (var i = 0; i < properties.length; i++) {
				var key = jQuery(properties[i]).attr("asu-property-key");
				var value = jQuery(properties[i]).val();
				
				this._properties[key] = value;
			}

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

            // обработчик кнопки выбора из списка
            var lookButton = jQuery(".icon-search", this);
            jQuery(lookButton).css("cursor", "pointer");
            jQuery(lookButton).on("click", this, this._onGlassButtonClick);
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
