{extends file="_core.component.tpl"}

{block name="asu_center"}
<h2>Редактирование задания</h2>

    {CHtml::helpForCurrentPage()}

{include file="_course_projects/tasks/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_course_projects/tasks/common.right.tpl"}
{/block}