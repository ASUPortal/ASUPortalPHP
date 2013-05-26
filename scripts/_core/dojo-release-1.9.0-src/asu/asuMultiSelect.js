/**
 * Created with JetBrains PhpStorm.
 * User: aleksandr
 * Date: 25.05.13
 * Time: 11:21
 * To change this template use File | Settings | File Templates.
 */
require([
    "dojo/_base/declare",
    "dojo/parser",
    "dojo/ready",
    "dijit/_WidgetBase",
    "dijit/form/FilteringSelect",
    "dijit/form/MultiSelect",
    "dojo/_base/array",
    "dojo/dom-construct"
], function(declare, parser, ready, _WidgetBase, array, domConstruct){

    declare("asu.asuMultiSelect", [_WidgetBase], {
        postCreate: function(){
            /**
             * Делаем выбиралки диджетами
             */
            this.selectDijit = new dijit.form.FilteringSelect({
                id: "_selector",
                name: "_selector",
                onChange: this._onSelectValue,
                style: "width: 30em"
            }, "_selector");
            this.displayDijit = new dijit.form.MultiSelect({
                id: "_display",
                style: "width: 362px; margin-left: 200px;"
            }, "_display");
            /**
             * Получаем значения из списков
             */
            this.serverFields = dojo.query("input[name='" + this.fieldName + "']", this.domNode);
            var serverValues = new Array();
            dojo.forEach(this.serverFields, function(field, index){
                serverValues[field.value] = field.value;
            });
            this.serverValues = serverValues;
            this.items = new Array();
            var items = this.items;
            dojo.forEach(this.selectDijit.store.data, function(obj){
                items[obj.id] = obj.name;
            });
            /**
             * В диджете выбора нам понадобятся эти значения
             */
            this.selectDijit._serverFields = this.serverFields;
            this.selectDijit._serverValues = this.serverValues;
            this.selectDijit._parentNode = this.domNode;
            this.selectDijit._displayDijit = this.displayDijit;
            this.selectDijit._items = this.items;
            this.selectDijit._fieldName = this.fieldName;
            /**
             * Не забываем навесить удалялку
             * Отдаем ей также всякие полезные параметры
             */
            this.deleteButton = dojo.byId("_deleter");
            dojo.connect(this.deleteButton, "onclick", this._onDeleteValue);
            this.deleteButton._serverFields = this.serverFields;
            this.deleteButton._serverValues = this.serverValues;
            this.deleteButton._displayDijit = this.displayDijit;
        },

        /**
         * Добавление значения в список
         *
         * @param itemIndex
         * @private
         */
        _onSelectValue: function(itemIndex){
            /**
             * Если текущего значения нет в списке - добавляем его туда,
             * создаем новый input
             */
            if (dojo.indexOf(this._serverValues, itemIndex) == -1) {
                var node = dojo.create("input", {
                    name: this._fieldName,
                    value: itemIndex,
                    type: "hidden"
                }, this._parentNode);
                /**
                 * Добавляем в списки
                 */
                this._serverFields[this._serverFields.length] = node;
                this._serverValues[itemIndex] = itemIndex;
                /**
                 * Добавляем в отображение
                 */
                var option = dojo.create("option", {
                    value: itemIndex,
                    innerHTML: this._items[itemIndex]
                }, this._displayDijit.domNode);
            }
        },
        _onDeleteValue: function(){
            var serverFields = this._serverFields;
            var serverValues = this._serverValues;
            var displayDijit = this._displayDijit;
            dojo.forEach(displayDijit.value, function(value, valIndex){
                dojo.forEach(serverFields, function(field){
                    if (field.value == value) {
                        dojo.destroy(field);
                    }
                });
                dojo.forEach(serverValues, function(val, index){
                    if (val == value) {
                        delete serverValues[index];
                    }
                });
                dojo.forEach(displayDijit.domNode.options, function(option, optIndex){
                    if (option.value == value) {
                        displayDijit.domNode.remove(optIndex);
                    }
                });
            });
        }
    });

    ready(function(){

    });
});