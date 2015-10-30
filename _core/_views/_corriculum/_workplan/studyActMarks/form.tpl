<form action="workplanmarksstudyactivity.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("activity_id", $object)}

    {CHtml::errorSummary($object)}

	<div class="control-group">
	    {CHtml::activeLabel("mark", $object)}
	    <div class="controls">
	        {CHtml::activeTextBox("mark", $object)}
	        {CHtml::error("mark", $object)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>