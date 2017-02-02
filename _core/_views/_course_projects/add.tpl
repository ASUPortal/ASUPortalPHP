{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление курсового проектирования</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_course_projects/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_course_projects/common.right.tpl"}
{/block}