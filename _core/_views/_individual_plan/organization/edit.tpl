{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование учебной и организационно-методической работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/organization/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/organization/edit.right.tpl"}
{/block}