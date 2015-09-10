{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление раздела дисциплины</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentSections/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentSections/common.right.tpl"}
{/block}