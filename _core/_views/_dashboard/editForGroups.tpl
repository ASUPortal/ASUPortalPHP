{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование записи для групп</h2>
{CHtml::helpForCurrentPage()}

{include file="_dashboard/formForGroups.tpl"}
{/block}

{block name="asu_right"}
{include file="_dashboard/list.right.tpl"}
{/block}