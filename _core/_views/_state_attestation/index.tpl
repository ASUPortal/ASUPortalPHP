{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Комиссии по защите дипломов</h2>

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
            var action = "index.php?action=index&filter=";
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
            var action = "index.php?action=index&filter=";
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
                var select = jQuery(value).find("input[type=checkbox]");
                if (select.length > 0) {
                    select = select[0];
                    jQuery(select).change(function(){
                        if (jQuery(select).is(":checked")) {
                            addFilter(jQuery(this).attr("id"), jQuery(this).val());
                        } else {
                            removeFilter(jQuery(this).attr("id"))
                        }
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
                        <label for="showall">Показывать комиссии прошлых лет</label>
                        {CHtml::checkBox("showall", 1, $showAll)}
                    </p>
                </form>
            </td>
            <td valign="top" width="200px">
                <p>

                </p>
            </td>
        </tr>
    </table>

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("title", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("year_id", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("secretar_id", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("manager_id", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("members", $commissions->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $commissions->getItems() as $commission}
            <tr>
                <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить комиссию {$commission->title}')) { location.href='?action=delete&id={$commission->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td valign="top">{counter}</td>
                <td valign="top"><a href="?action=edit&id={$commission->getId()}">{$commission->title}</a></td>
                <td valign="top">
                    {if !is_null($commission->year)}
                        {$commission->year->getValue()}
                    {/if}
                </td>
                <td valign="top"
                    {if !is_null($commission->secretar)}
                        {$commission->secretar->getName()}
                    {/if}
                </td>
                <td valign="top">
                    {if !is_null($commission->manager)}
                        {$commission->manager->getName()}
                    {/if}
                </td>
                <td valign="top">
                    <ul>
                    {foreach $commission->members->getItems() as $member}
                        <li>{$member->getName()}</li>
                    {/foreach}
                    </ul>
                </td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_state_attestation/index.right.tpl"}
{/block}