{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Генератор кода</h2>

    {CHtml::helpForCurrentPage()}
{/block}

{block name="asu_right"}
    {include file="__generator/index.right.tpl"}
{/block}