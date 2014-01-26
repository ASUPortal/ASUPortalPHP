{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление #viewObjectSingleNameRP#</h2>

    {CHtml::helpForCurrentPage()}

    {include file="#viewPath#/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="#viewPath#/common.right.tpl"}
{/block}