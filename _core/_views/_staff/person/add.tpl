{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление сотрудника</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/person/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/person/add.right.tpl"}
{/block}