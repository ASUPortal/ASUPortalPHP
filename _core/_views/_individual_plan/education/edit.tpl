{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование учебно-воспитательной работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/education/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/education/edit.right.tpl"}
{/block}