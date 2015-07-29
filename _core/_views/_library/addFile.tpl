{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление файла</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_library/formFile.tpl"}
{/block}

{block name="asu_right"}
    {include file="_library/add.right.tpl"}
{/block}