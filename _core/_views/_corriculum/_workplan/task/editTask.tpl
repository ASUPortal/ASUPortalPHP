{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование задачи рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/task/formTask.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/task/common.right.tpl"}
{/block}