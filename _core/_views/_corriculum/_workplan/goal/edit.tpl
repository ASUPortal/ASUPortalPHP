{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование цели рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/goal/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/goal/common.right.tpl"}
{/block}