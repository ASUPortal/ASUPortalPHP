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
        var _allowCreation = false;
        var _lookupDialog = null;

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
					action: "LookupGetDialog",
                    allowCreation: parentObj._allowCreation
				},
                context: parentObj,
                success: function(data){
                    this._lookupDialog = jQuery(data);
                    jQuery(this._lookupDialog).on("show", this, this._onDialogShow);
                    jQuery(this._lookupDialog).on("shown", this, this._onDialogShown);
                    jQuery("[asu-action=ok]", this._lookupDialog).on("click", this, this._onDialogOkClick);
                    jQuery("[asu-action=search]", this._lookupDialog).on("change", this, this._onDialogSearchChange);
                    jQuery("[asu-action=create]", this._lookupDialog).on("click", this, this._onDialogCreateClick);
                    jQuery(this._lookupDialog).modal();
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
            if (!this._isMultiple) {
                // выбор не множественный снимаем все остальные выбиралки
                var selected = jQuery(event.srcElement).parents("table").find("input:checked");
                jQuery.each(selected, function(){
                    jQuery(this).attr("checked", false);
                });
            }
            // выбираем чекбокс в текущей строке
            var checkbox = jQuery(event.srcElement).parents("tr").find("input[type=checkbox]");
            jQuery(checkbox).attr("checked", true);
        };

        // создать в диалоге
        this._onDialogCreateClick = function(event){
            var parentObj = event.data;

            // будем загружать новый диалог с формой
            var dialog = bootbox.dialog('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>', [{
                "label": "Отмена",
                "class": "btn",
                "callback": function(){
                    // при отмене ничего не происходит
                }
            }, {
                "label": "ОК",
                "class": "btn-primary",
                "callback": function(event){
                    parentObj.handlerOnCreationDialogOkClick();
                    // диалог мы будем закрывать самостоятельно
                    return false;
                }
            }]);

            // прицепляем функции
            parentObj.handlerGetCreationForm = getCreationForm;
            parentObj.handlerPrepareFormHTMLFormDisplay = prepareFormHTMLFormDisplay;
            parentObj.handlerInsertHTHMLToDialog = insertHTHMLToDialog;
            parentObj.handlerDialog = dialog;
            parentObj.handlerOnCreationDialogOkClick = onCreationDialogOkClick;
            parentObj.handlerForm = null;
            parentObj.handlerFormTargetURL = "";
            parentObj.handlerAfterFormSend = afterFormSend;
            parentObj.sourceDialogURL = "";
            parentObj.sourceAddAction = "save";
            parentObj.handlerCloseDialogAndRefreshParent = closeDialogAndRefreshParent;

            // запускаем их
            jQuery(dialog).on("shown", null, this, getCreationFormURL);
            jQuery(dialog).on("shown", null, dialog, function(e){
                // фиксим размер диалога
                jQuery(this).animate({
                    width: "800px",
                    "margin-left": "-400px"
                });
            });

            /**
             * Получаем адрес страницы, с которой нужно получить форму
             * для добавления
             */
            function getCreationFormURL(event) {
                jQuery.ajax({
                    cache: false,
                    url: web_root + "_modules/_search/",
                    dataType: "html",
                    data: {
                        action: "LookupGetCreationDialog",
                        catalog: parentObj._catalog
                    },
                    success: function(data){
                        parentObj.handlerGetCreationForm(data);
                    }
                });
            };
            /**
             * Получаем форму с указанной страницы и загружаем ее
             * в диалог
             *
             * @param url
             */
            function getCreationForm(url) {
                jQuery.ajax({
                    cache: false,
                    url: url,
                    dataType: "html",
                    success: function(data){
                        parentObj.handlerPrepareFormHTMLFormDisplay(data, url);
                    }
                });
            };
            /**
             * Подготавливаем форму к отображению
             *
             * @param html
             * @param sourceURL
             */
            function prepareFormHTMLFormDisplay(html, sourceURL) {
                var targetHtml = jQuery("#asu_body_content form", html);
                // убиваем дополнительные кнопки
                var buttons = jQuery(".btn-primary", targetHtml);
                var buttonsRow = jQuery(buttons).parents(".control-group");
                jQuery(buttonsRow).remove();
                // формируем адрес, на который нужно оптравить форму теперь
                parentObj.handlerFormTargetURL = sourceURL.substring(0, sourceURL.lastIndexOf("/"));
                parentObj.handlerFormTargetURL += "/" + jQuery(targetHtml).attr("action");
                // на всякий случай сохраним исходный адрес диалога
                parentObj.sourceDialogURL = sourceURL;
                // отпределяем, какое действие используется в диалоге для сохранения результата
                parentObj.sourceAddAction = jQuery("input[name=action]", targetHtml).val();

                parentObj.handlerInsertHTHMLToDialog(targetHtml);
            };
            /**
             * Вклеиваем форму создания в диалог
             *
             * @param html
             */
            function insertHTHMLToDialog(html){
                parentObj.handlerForm = html;
                jQuery(".modal-body", parentObj.handlerDialog).html(html);
            };
            /**
             * Слушалка нажатия на ОК в диалоге создания
             */
            function onCreationDialogOkClick(){
                // затемняем диалог, чтобы пользователь 20 раз ОК не нажал
                jQuery(jQuery(".modal-body", parentObj.handlerDialog)).animate({
                    opacity: 0.1
                });
                jQuery("a.btn", parentObj.handlerDialog).addClass("disabled");
                // нам надо, чтобы action сменился при успешной отправке формы
                var formData = jQuery(parentObj.handlerForm).serialize();
                formData["_continueEdit"] = "0";
                jQuery.ajax({
                    url: parentObj.handlerFormTargetURL,
                    data: formData,
                    dataType: "html",
                    type: "POST",
                    success: function(data, status, xhr){
                        parentObj.handlerAfterFormSend(data);
                    }
                });
            };
            /**
             * Отрабатывает, когда пришли данные сохранения
             * данных в диалоге
             *
             * @param html
             */
            function afterFormSend(html){
                // если в форме есть action, совпадающий с исходным action-ом добавления,
                // то форма не была успешно отправлена
                var isSuccess = true;
                var form = jQuery("#asu_body_content form", html);
                if (form.length > 0) {
                    var input = jQuery("input[name=action]", form);
                    isSuccess = jQuery(input).val() != parentObj.sourceAddAction;
                }
                if (!isSuccess) {
                    // делаем форму нормальной обратно
                    jQuery(jQuery(".modal-body", parentObj.handlerDialog)).animate({
                        opacity: 1
                    });
                    jQuery("a.btn", parentObj.handlerDialog).removeClass("disabled");
                    parentObj.handlerPrepareFormHTMLFormDisplay(html, parentObj.sourceDialogURL);
                    return false;
                }
                // а тут успешное завершение диалога
                parentObj.handlerCloseDialogAndRefreshParent();
            };
            /**
             * Закрываем диалог и обновляем родительский диалог выбора
             * из представления
             */
            function closeDialogAndRefreshParent() {
                // костылик для совместимости со старыми функциями
                var event = {
                    data: parentObj
                };

                parentObj._onDialogShown(event);
                jQuery(parentObj.handlerDialog).modal("hide");
            };
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
            $("tr").show();
            // обновляем представление
            event.data._updateDisplay();
            // закрываем диалог
            jQuery(dialog).modal("hide");
        };

        // search в диалоге
        this._onDialogSearchChange = function(event){
        	var item_search = $('input[name="search_id"]').val();
        	$("tr").not(":contains(' + item_search + ')").hide();
        	$('tr:contains(' + item_search + ')').show();
        };

        // данные для диалога с сервера получены
        this._onDialogDataReceived = function(data){
            // загружаем полученные данные
            var container = jQuery(".modal-body", this._lookupDialog);
            jQuery(container).empty();
            var table = jQuery("<table/>",{
                "class": "table table-hover table-condensed"
            });
            var body = jQuery("<tbody/>");
            // для каждой полученной записи добавляем строку
            var index = 1;
            var parent = this;
            jQuery.each(data, function(key, value){
                var row = jQuery("<tr/>");
                jQuery(row).css("cursor", "pointer");
                jQuery(row).on("click", jQuery.proxy(parent._onDialogRowClick, parent));
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
        };

        // обновление диалога при смене параметров
        this._onPropertiesChange = function(event){
            // покажем загрузчик в диалоге
            var place = jQuery(".modal-body", this._lookupDialog);
            jQuery(place).html('<div style="text-align: center;"><img src="' + web_root + 'images/loader.gif"></div>');
            // получим выбранные параметры
            var params = new Array();
            jQuery.each(jQuery(".modal-properties-box input:checked", this._lookupDialog), function(key, item){
                params[params.length] = jQuery(item).val();
            });
            // отправим запрос на сервер вместе с параметрами
            var xhrData = this._properties;
            xhrData["action"] = "LookupViewData";
            xhrData["catalog"] = this._catalog;
            xhrData["properties"] = params;

            var that = this;
            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "json",
                data: xhrData,
                context: this,
                success: function(data){
                    this._onDialogDataReceived(data);
                }
            });
        };

        // загрузка параметров каталога в диалог
        this._onDialogShown = function(event){
            this.parentObject = event.data;
            /**
             * поменяем местами запросы. сначала делаем запрос
             * на получение параметров каталога
             */
            jQuery.ajax({
                cache: false,
                url: web_root + "_modules/_search/",
                dataType: "json",
                data:  {
                    action: "LookupGetCatalogProperties",
                    catalog: this.parentObject._catalog
                },
                context: this,
                success: function(properties){
                    // загружаем параметры каталога в выбиратор
                    var parent = this.parentObject;
                    var container = jQuery(".modal-properties-box", this._lookupDialog);
                    jQuery(container).empty();
                    jQuery.each(properties, function(key, property){
                        var wrapper = jQuery("<label/>", {
                            "class": "checkbox inline"
                        }).text(property.label);
                        var checker = jQuery("<input/>", {
                            "type": "checkbox",
                            "value": property.key,
                            "checked": property.checked
                        });
                        jQuery(checker).appendTo(wrapper);
                        jQuery(wrapper).appendTo(container);
                        // вешаем обновление содержимого при нажатии на галку
                        jQuery(checker).on("change", jQuery.proxy(parent._onPropertiesChange, parent));
                    });
                    // загружаем данные в соответствии с нажатыми галочками
                    jQuery.proxy(parent._onPropertiesChange(), parent);
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
                                    "index": key,
                                    "style": "white-space: normal"
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
                var realItemName = itemName;
                if (itemHolder._isMultiple) {
                    realItemName += "[" + index + "]";
                }
                var input = jQuery("<input/>", {
                    "type": "hidden",
                    "name": realItemName,
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

            // возможность создания
            this._allowCreation = (jQuery(this).attr("asu-creation") == "true");
			
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
