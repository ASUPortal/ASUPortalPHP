{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление заданий</h2>

    {CHtml::helpForCurrentPage()}
    
    <form action="task.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "saveGroup")}
    {CHtml::hiddenField("id", $courseProject->getId())}
    
    {CHtml::errorSummary($courseProject)}
    
		<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>{CHtml::tableOrder("student_id", $courseProject->tasks->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("theme", $courseProject->tasks->getFirstItem())}</th>
	        </tr>
	        {foreach $courseProject->tasks->getItems() as $task}
	            <tr>
	                <td width="40%">
	                    {CHtml::textField($task->getId(), $task->student->getName(), "", "", 'style="width: 100%;"')}
	                </td>
	                <td width="60%">
	                    {CHtml::textField($task->getId(), "", "", "", 'style="width: 100%;"')}
	                </td>
	            </tr>
	        {/foreach}
		</table>
	
	    <div class="control-group">
	        <div class="controls">
	            {CHtml::submit("Сохранить", false)}
	        </div>
	    </div>
	</form>

{/block}

{block name="asu_right"}
	{include file="_course_projects/tasks/common.right.tpl"}
{/block}