{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Описатели полей</h2>

    <script>
        function getFilters() {
            filters = new Object();
            var items = jQuery("#filters p");
            jQuery.each(items, function(key, value){
                // получаем название фильтра, он хранится в label-е
                var label = jQuery(value).find("label");
                var filter_name = "";
                if (label.length > 0) {
                    filter_name = jQuery(label[0]).attr("for");
                }
                var filter_value = "";
                var val = jQuery(value).find("select");
                if (val.length > 0) {
                    filter_value = jQuery(val[0]).val();
                } else {
                    val = jQuery(value).find("input");
                    if (val.length > 0) {
                        filter_value = jQuery(val[0]).val();
                    }
                }
                /**
                 * Если есть что добавлять, то добавляем
                 */
                if (filter_name !== "") {
                    filters[filter_name] = filter_value;
                }
            });
            return filters;
        }
        /**
         * Очистка указанного фильтра
         * @param type
         */
        function removeFilter(type) {
            var filters = getFilters();
            var action = "field.php?action=index&filter=";
            var actions = new Array();
            jQuery.each(filters, function(key, value){
                if (key !== type) {
                    actions[actions.length] = key + ":" + value;
                }
            });
            action = action + actions.join("_");
            window.location.href = action;
        }
        function addFilter(key, value) {
            var filters = getFilters();
            var action = "field.php?action=index&filter=";
            var actions = new Array();
            filters[key] = value;
            jQuery.each(filters, function(filter_key, filter_value){
                actions[actions.length] = filter_key + ":" + filter_value;
            });
            action = action + actions.join("_");
            window.location.href = action;
        }
        jQuery(document).ready(function(){
            /**
             * Добавляем автопоиск
             */
            jQuery("#search").autocomplete({
                source: web_root + "_modules/_print/field.php?action=search",
                minLength: 2,
                select: function(event, ui) {
                    window.location.href= "?action=index&filter=" + ui.item.filter + ":" + ui.item.object_id;
                }
            });
            /**
             * Для всех опубликованных фильтров добавляем
             * автоматический переключатель
             */
            var items = jQuery("#filters p");
            jQuery.each(items, function(key, value){
                var input = jQuery(value).find("select");
                if (input.length > 0) {
                    input = input[0];
                    jQuery(input).change(function(){
                        addFilter(jQuery(this).attr("id"), jQuery(this).val());
                    });
                }
            });
        });
    </script>

    <table border="0" width="100%" class="tableBlank">
        <tr>
            <td valign="top">
                <form id="filters">
                    <p>
                        <label for="formset">Набор шаблонов</label>
                        {CHtml::dropDownList("formsets", $formsets, $selectedFormset, "formset")}
                        {if !is_null($selectedFormset)}
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('formset'); return false; "/></span>
                        {/if}
                    </p>
                    {if !is_null($selectedField)}
                        <p>
                            <label for="field">Поле</label>
                            <input type="hidden" name="field" value="{$selectedField->getId()}">
                            {$selectedField->title} ({$selectedField->alias})
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('field'); return false; "/></span>
                        </p>
                    {/if}
                </form>
            </td>
            <td valign="top" width="200px">
                <p>
                    <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
                </p>
            </td>
        </tr>
    </table>

<table width="100%" cellpadding="2" cellspacing="0" border="1" id="table">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Набор форм</th>
        <th></th>
    </tr>

    {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $fields->getItems() as $field}
        <tr>
            <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить описатель поля {$field->title}')) { location.href='?action=delete&id={$field->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="field.php?action=edit&id={$field->id}">{$field->title}<a/> ({$field->alias})</td>
            <td valign="top">{$field->description|nl2br}</td>
            <td valign="top">{$field->formset->title}</td>
            <td><input type="checkbox" name="selected[]" value="{$field->getId()}"></td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/field/index.right.tpl"}
{/block}