{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование записи</h2>
{CHtml::helpForCurrentPage()}

{include file="_gradebook/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_gradebook/add.right.tpl"}
{/block}