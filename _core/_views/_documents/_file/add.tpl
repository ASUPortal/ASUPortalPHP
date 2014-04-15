{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление файла</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_documents/_file/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_documents/_file/common.right.tpl"}
{/block}