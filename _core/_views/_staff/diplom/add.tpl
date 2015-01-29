{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление ВКР сотрудника</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/diplom/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/diplom/add.right.tpl"}
{/block}