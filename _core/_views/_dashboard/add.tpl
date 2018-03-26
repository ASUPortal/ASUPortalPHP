{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление новой записи</h2>
{CHtml::helpForCurrentPage()}

    {include file="_dashboard/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_dashboard/common.right.tpl"}
{/block}