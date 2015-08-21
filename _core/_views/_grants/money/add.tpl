{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление расхода/поступления</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_grants/money/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_grants/money/add.right.tpl"}
{/block}