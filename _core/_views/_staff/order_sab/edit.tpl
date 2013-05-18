{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование приказа по ГАК</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/order_sab/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/order_sab/edit.right.tpl"}
{/block}