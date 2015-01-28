{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование предзащиты</h2>

    {CHtml::helpForCurrentPage()}

{include file="_diploms/diplom_preview/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_diploms/diplom_preview/edit.right.tpl"}
{/block}