{extends file="_core.component.tpl"}

{block name="asu_center"}
<h2>Задания</h2>

    {CHtml::helpForCurrentPage()}
    
{if $tasks->getCount() == 0}
	Нет данных для отображения
{else}
	<form action="task.php" method="post" id="mainViewTasks">
		<table class="table table-striped table-bordered table-hover table-condensed">
		    <tr>
		    	<th>&nbsp;</th>
		        <th>#</th>
		        <th>{CHtml::activeViewGroupSelect("id", $tasks->getFirstItem(), true)}</th>
		        <th>{CHtml::tableOrder("student_id", $tasks->getFirstItem())}</th>
		        <th>{CHtml::tableOrder("theme", $tasks->getFirstItem())}</th>
		        <th>{CHtml::tableOrder("mark", $tasks->getFirstItem(), true)}</th>
		    </tr>
		    {counter start=0 print=false}
		    {foreach $tasks->getItems() as $task}
		        <tr>
		        	<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить задание')) { location.href='task.php?action=delete&id={$task->id}'; }; return false;"></a></td>
		            <td>{counter}</td>
		            <td>{CHtml::activeViewGroupSelect("id", $task)}</td>
		            <td>
		                <a href="task.php?action=edit&id={$task->getId()}">
		                    {$task->student->getName()}
		                </a>
		            </td>
		            <td>{$task->theme}</td>
		            <td>
		            	{if !is_null(CStaffService::getStudentActivityByTypeAndDate($task->student, $task->courseProject->discipline, $task->courseProject->lecturer, 
		                    	CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $task->courseProject->issue_date))}
		            		{CStaffService::getStudentActivityByTypeAndDate($task->student, $task->courseProject->discipline, $task->courseProject->lecturer, 
		                    	CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $task->courseProject->issue_date)->mark->getValue()}
		            	{else}
		            		Записи об успеваемости нет
		            	{/if}
		            </td>
		        </tr>
		    {/foreach}
		</table>
	</form>
{/if}
    
{/block}

{block name="asu_right"}
	{include file="_course_projects/tasks/common.right.tpl"}
{/block}