{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование комиссии</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_diploms/preview_commission/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_diploms/preview_commission/edit.right.tpl"}
{/block}