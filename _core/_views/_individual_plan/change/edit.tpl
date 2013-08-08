{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование изменения в годовом индивидуальном плане</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/change/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/change/edit.right.tpl"}
{/block}