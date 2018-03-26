{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Элементы рабочего стола для групп пользователей</h2>
{CHtml::helpForCurrentPage()}

<table class="table table-striped table-bordered table-hover table-condensed">
	<tr>
		<th width="5"></th>
		<th width="5">#</th>
		<th width="16">Значок</th>
		<th>Название</th>
		<th>Группа пользователей</th>
	</tr>
	{foreach $items->getItems() as $item}
	<tr>
		<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить ссылку {$item->title}')) { location.href='?action=delete&id={$item->id}'; }; return false;"></a></td>
		<td>{counter}</td>
		<td>
			{if ($item->icon == "")}
				&nbsp;
			{else}
				<center><img src="{$web_root}images/{$icon_theme}/16x16/{$item->icon}"></center>
			{/if}
		</td>
		<td><a href="?action=edit&id={$item->id}&forGroups=1">{$item->title}</a></td>
		<td>{CStaffManager::getUserGroup($item->group_id)->comment}</td>
	</tr>
		{foreach $item->children->getItems() as $child}
		<tr>
			<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить ссылку {$child->title}')) { location.href='?action=delete&id={$child->id}'; }; return false;"></a></td>
			<td>{counter}</td>
			<td>&nbsp;</td>
			<td>- <a href="?action=edit&id={$child->id}&forGroups=1">{$child->title}</a></td>
			<td></td>
		</tr>		
		{/foreach}
	{/foreach}
</table>

	{CHtml::paginator($paginator, "?action=list")}
{/block}

{block name="asu_right"}
{include file="_dashboard/common.right.tpl"}
{/block}