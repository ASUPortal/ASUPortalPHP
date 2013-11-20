{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/work//form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/work//edit.right.tpl"}
{/block}