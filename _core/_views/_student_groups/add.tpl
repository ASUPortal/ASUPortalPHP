{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление студенческой группы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_student_groups/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_student_groups/add.right.tpl"}
{/block}