{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление учебника</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_books/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_books/common.right.tpl"}
{/block}