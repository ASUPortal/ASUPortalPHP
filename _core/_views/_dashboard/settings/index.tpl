{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Элементы рабочего стола пользователей</h2>
	{CHtml::helpForCurrentPage()}
	<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedUser)}
            filters.user = {$selectedUser};
        {/if}
        {if !is_null($selectedGroup)}
            filters.group = {$selectedGroup};
        {/if}
        var action = "?action=index&filter=";
        var actions = new Array();
        jQuery.each(filters, function(key, value){
            if (key !== type) {
                actions[actions.length] = key + ":" + value;
            }
        });
        action = action + actions.join("_");
        window.location.href = action;
    }
	jQuery(document).ready(function() {
	   	var filters = new Object();
	   	{if !is_null($selectedUser)}
	   		filters.user = {$selectedUser};
	   	{/if}
	   	{if !is_null($selectedGroup)}
            filters.group = {$selectedGroup};
        {/if}
	       function updateFilter() {
	       	var query = new Array();
	       	var filter = new Array();
	       	$.each(filters, function(key, value){
	       		if (value != 0) {
	           		filter[filter.length] = key + ":" + value;	
	       		}
	       	});
	       	query[query.length] = "action=index";
	       	query[query.length] = "filter=" + filter.join("_");
	       	window.location.href = "settings.php?" + query.join("&");
	       }
	   	$("#user_id").change(function(){
	   		filters.user = $(this).val();
	   		updateFilter();
	   	});
    	$("#group_id").change(function(){
    		filters.group = $(this).val();
    		updateFilter();
    	});
    	jQuery("#selectAll").change(function(){
    		var items = jQuery("input[name='selectedDoc[]']")
            for (var i = 0; i < items.length; i++) {
                items[i].checked = this.checked;
            }
        });
	});
	</script>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td>
				<div class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="person">Пользователь</label>
						<div class="controls">
							{CHtml::dropDownList("user", $users, $selectedUser, "user_id", "span12")} 
						</div>
					</div>
				</div>
			</td>
			<td valign="top" width="5%">
				<p align="center">
					<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('user'); return false; " title="очистить фильтр"/></span>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<div class="form-horizontal">
					<div class="control-group">
            			<label class="control-label" for="group">Группа</label>
	           			<div class="controls">
                			{CHtml::dropDownList("group", $usersGroups, $selectedGroup, "group_id", "span12")}
            			</div>
        			</div>
    			</div>
    		</td>
			<td valign="top" width="5%">
				<p align="center">
						<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; " title="очистить фильтр"/></span>
				</p>
			</td>
		</tr>
	</table>
	<form action="settings.php" method="post" id="MainView">
	<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<th width="5"></th>
			<th width="5"><input type="checkbox" id="selectAll"></th>
			<th width="5">#</th>
			<th width="16">Значок</th>
			<th>Название</th>
			<th>{CHtml::tableOrder("user.FIO", $items->getFirstItem(), true)}</th>
		</tr>
		{foreach $items->getItems() as $item}
		<tr>
			<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить ссылку {$item->title}')) { location.href='?action=delete&id={$item->id}'; }; return false;"></a></td>
			<td>
				<input type="checkbox" value="{$item->getId()}" name="selectedDoc[]">
			</td>
			<td>{counter}</td>
			<td>
				{if ($item->icon == "")}
					&nbsp;
				{else}
					<center><img src="{$web_root}images/{$icon_theme}/16x16/{$item->icon}"></center>
				{/if}
			</td>
			<td><a href="?action=edit&id={$item->id}&user_id={$item->user_id}">{$item->title}</a></td>
			<td><a href="?action=add&id={$item->user_id}" title="добавить элемент">{CStaffManager::getUserById($item->user_id)->FIO}</td>
		</tr>
			{foreach $item->children->getItems() as $child}
			<tr>
				<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить ссылку {$child->title}')) { location.href='?action=delete&id={$child->id}'; }; return false;"></a></td>
				<td>
					<input type="checkbox" value="{$child->getId()}" name="selectedDoc[]">
				</td>
				<td></td>
				<td>&nbsp;</td>
				<td>- <a href="?action=edit&id={$child->id}&user_id={$child->user_id}">{$child->title}</a></td>
				<td></td>
			</tr>		
			{/foreach}
		{/foreach}
	</table>
	</form>

	{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
	{include file="_dashboard/settings/index.right.tpl"}
{/block}