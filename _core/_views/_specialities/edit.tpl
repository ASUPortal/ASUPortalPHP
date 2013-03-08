{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование специальности</h2>

    {CHtml::helpForCurrentPage()}

{include file="_specialities/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_specialities/edit.right.tpl"}
{/block}