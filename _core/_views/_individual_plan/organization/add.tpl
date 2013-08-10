{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление учебной и организационно-методической работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/organization/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/organization/add.right.tpl"}
{/block}