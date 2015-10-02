{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление категории</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentCategories/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentCategories/common.right.tpl"}
{/block}