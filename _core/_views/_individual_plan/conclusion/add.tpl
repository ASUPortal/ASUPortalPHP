{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление заключения заведующего кафедрой</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/conclusion/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/conclusion/add.right.tpl"}
{/block}