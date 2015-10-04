{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование темы расчётно-графической работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/rgrThemes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/rgrThemes/common.right.tpl"}
{/block}