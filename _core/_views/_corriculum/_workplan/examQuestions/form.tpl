<form action="workplanexamquestions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("type", $object)}

    {CHtml::errorSummary($object)}
    
    <div class="control-group">
	    {CHtml::activeLabel("question", $object)}
	    <div class="controls">
	        {CHtml::activeTextBox("question", $object)}
	        {CHtml::error("question", $object)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>