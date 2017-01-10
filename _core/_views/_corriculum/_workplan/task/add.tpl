{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление задачи рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/task/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/task/common.right.tpl"}
{/block}