{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление расчётного задания</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/calculationTasks/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/calculationTasks/common.right.tpl"}
{/block}