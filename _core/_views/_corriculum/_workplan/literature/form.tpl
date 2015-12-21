<form action="workplanliterature.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("type", $object)}

    {CHtml::errorSummary($object)}
    
	<div class="control-group">
		{CHtml::activeLabel("ordering", $object)}
		<div class="controls">
			{CHtml::activeTextField("ordering", $object)}
			{CHtml::error("ordering", $object)}
		</div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("books", $object)}
	    <div class="controls">
	        {CHtml::activeLookup("books", $object, "class.CSearchCatalogCorriculumBooks", true, ["plan_id" => $object->plan_id])}
	        {CHtml::error("books", $object)}
	    </div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>