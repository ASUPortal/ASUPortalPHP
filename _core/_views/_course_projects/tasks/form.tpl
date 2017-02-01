<form action="task.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $task)}
    {CHtml::activeHiddenField("course_project_id", $task)}

    {CHtml::errorSummary($task)}
    
    	<div class="control-group">
	    {CHtml::activeLabel("student_id", $task)}
	    <div class="controls">
	        {CHtml::activeDropDownList("student_id", $task, $students)}
	        {CHtml::error("student_id", $task)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("theme", $task)}
	    <div class="controls">
	        {CHtml::activeTextField("theme", $task)}
	        {CHtml::error("theme", $task)}
	    </div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>