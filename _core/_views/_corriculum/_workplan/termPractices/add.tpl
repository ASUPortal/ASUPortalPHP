{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление практики</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termPractices/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termPractices/common.right.tpl"}
{/block}