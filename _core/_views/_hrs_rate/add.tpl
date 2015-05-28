{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление ставки</h2>
    {CHtml::helpForCurrentPage()}
    {include file="_hrs_rate/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_hrs_rate/add.right.tpl"}
{/block}