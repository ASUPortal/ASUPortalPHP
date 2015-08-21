{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Поиск записей</h2>
{CHtml::helpForCurrentPage()}

    {include file="_gradebook/gradebook.form.tpl"}
{/block}

{block name="asu_right"}
{include file="_gradebook/gradebook.create.right.tpl"}
{/block}