{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление учебника</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_books/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_books/common.right.tpl"}
{/block}