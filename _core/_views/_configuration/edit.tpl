{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование настройки портала</h2>

    {CHtml::helpForCurrentPage()}

{include file="_configuration/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_configuration/edit.right.tpl"}
{/block}