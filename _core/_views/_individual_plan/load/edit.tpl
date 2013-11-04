{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование учебной нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/load/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/edit.right.tpl"}
{/block}