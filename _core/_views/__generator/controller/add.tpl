{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Генератор модулей</h2>

    {CHtml::helpForCurrentPage()}

    {include file="__generator/controller/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="__generator/controller/add.right.tpl"}
{/block}