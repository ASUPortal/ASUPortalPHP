{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление раздела</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termSections/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termSections/common.right.tpl"}
{/block}