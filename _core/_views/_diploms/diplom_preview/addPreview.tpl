{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление предзащиты</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_diploms/diplom_preview/formPreview.tpl"}
{/block}

{block name="asu_right"}
{include file="_diploms/diplom_preview/add.right.tpl"}
{/block}
