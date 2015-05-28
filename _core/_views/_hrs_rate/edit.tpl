{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование ставки</h2>
    {CHtml::helpForCurrentPage()}
    {include file="_hrs_rate/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_hrs_rate/edit.right.tpl"}
{/block}