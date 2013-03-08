{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование студента</h2>

    {CHtml::helpForCurrentPage()}

{include file="_students/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_students/edit.right.tpl"}
{/block}