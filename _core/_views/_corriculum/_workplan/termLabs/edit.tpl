{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование лабораторной работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termLabs/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termLabs/common.right.tpl"}
{/block}