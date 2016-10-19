<form action="workplancalculationtasks.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("section_id", $object)}

    {CHtml::errorSummary($object)}
    
	<div class="control-group">
        {CHtml::activeLabel("task", $object)}
        <div class="controls">
            {CHtml::activeTextBox("task", $object, "task")}
            {CHtml::error("task", $object)}
        </div>
    </div>
	
	<div class="control-group">
		{CHtml::activeLabel("ordering", $object)}
		<div class="controls">
			{CHtml::activeTextField("ordering", $object)}
			{CHtml::error("ordering", $object)}
		</div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
    
    <script>
	    jQuery(document).ready(function(){
	        jQuery("#task").redactor({
	            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
	        });
	    });
	</script>
</form>