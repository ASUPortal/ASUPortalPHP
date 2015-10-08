{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование темы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/projectThemes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/projectThemes/common.right.tpl"}
{/block}