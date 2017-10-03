{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Приказы УГАТУ</h2>

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
             * Добавляем автопоиск
             */
            jQuery("#search").autocomplete({
                source: web_root + "_modules/_orders_usatu/index.php?action=search",
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

<form>
	<table border="0" width="100%" class="tableBlank">
	    <tr>  
            <td valign="top">
	        	{CHtml::hiddenField("action", "index")}
	        	<label>Поиск по приказам</label>
	            {CHtml::textField("textSearch", $textSearch, "", "", "placeholder=Поиск")}
	        </td>
	    </tr>
	    <tr>
	        <td valign="top">
		    	<div class="controls">
					<input name="" type="submit" class="btn" value="Найти">
				</div>	
			</td>
	    </tr>
	</table>
</form>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("orders_type", $orders->getFirstItem())}</th>
        <th>{CHtml::tableOrder("date", $orders->getFirstItem())}</th>
        <th>{CHtml::tableOrder("title", $orders->getFirstItem())}</th>
        <th>Комментарий</th>
    </tr>
    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $orders->getItems() as $order}
    <tr>
        <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить приказ {$order->title}')) { location.href='?action=delete&id={$order->id}'; }; return false;"></a></td>
        <td>{counter}</td>
        <td>
            {if !is_null($order->type)}
                {$order->type->getValue()}
            {/if}
        </td>
        <td>
            <a href="?action=edit&id={$order->getId()}">№{$order->num} от {$order->date}</a>
        </td>
        <td>
            <p><b>{$order->title}</b></p>
            <p>{$order->text}</p>
        </td>
        <td>{$order->comment}</td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_orders_usatu/index.right.tpl"}
{/block}