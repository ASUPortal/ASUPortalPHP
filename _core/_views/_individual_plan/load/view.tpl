{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальный план</h2>

    {CHtml::helpForCurrentPage()}

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
            var action = "load.php?action=index&id={$person->getId()}&filter=";
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
            var action = "load.php?action=view&id={$person->getId()}&filter=";
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
                        <label for="year">Год</label>
                        {CHtml::dropDownList("year", $years, $selectedYear, "year")}
                        {if !is_null($selectedYear)}
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('year'); return false; "/></span>
                        {/if}
                    </p>
                </form>
            </td>
        </tr>
    </table>

    {if $person->getIndPlansByYears()->getCount() == 0}
        Нет информации для отображения
    {else}
        {foreach $person->getIndPlansByYears()->getItems() as $load}
            {include file="_individual_plan/load/subform.yearLoad.tpl"}
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/view.right.tpl"}
{/block}