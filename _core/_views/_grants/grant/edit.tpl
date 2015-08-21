{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование гранта</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_grants/grant/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_grants/grant/edit.right.tpl"}
{/block}