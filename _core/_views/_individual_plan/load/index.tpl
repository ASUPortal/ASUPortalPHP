{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные планы преподавателей</h2>

    {CHtml::helpForCurrentPage()}

    {if $persons->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}
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
                var action = "load.php?action=index&filter=";
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
                var action = "load.php?action=index&filter=";
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

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>{CHtml::tableOrder("fio", $persons->getFirstItem())}</th>
                <th>Год</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $persons->getItems() as $person}
            <tr>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">{counter}</td>
                <td rowspan="{$person->getIndPlansByYears()->getCount() + 1}">
                    <a href="load.php?action=view&id={$person->getId()}">
                        {$person->fio}
                    </a>
                </td>
                {if $person->getIndPlansByYears()->getCount() == 0}
                    <td>Нет информации</td>
                {/if}
            </tr>
                {foreach $person->getIndPlansByYears()->getItems() as $load}
                    <tr>
                        <td>
                            {if !is_null($load->year)}
                                <a href="load.php?action=view&id={$person->getId()}&year={$load->year->getId()}">
                                    {$load->year->getValue()}
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/index.right.tpl"}
{/block}