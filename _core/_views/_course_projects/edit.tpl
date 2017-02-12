{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование курсового проектирования</h2>

    {CHtml::helpForCurrentPage()}

	{include file="_course_projects/form.tpl"}
	
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tasks">Журнал учета выдачи студентам заданий</a></li>
    </ul>
    <div class="tab-content">
        <div id="tasks" class="tab-pane active">
            {CHtml::activeComponent("task.php?course_project_id={$courseProject->getId()}", $courseProject)}
        </div>
    </div>
{/block}

{block name="asu_right"}
	{include file="_course_projects/edit.right.tpl"}
{/block}