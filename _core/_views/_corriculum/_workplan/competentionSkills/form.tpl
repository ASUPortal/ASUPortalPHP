<form action="workplancompetentionskills.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("competention_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("skill_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("skill_id", $object, "corriculum_knowledges", false, array(), true)}
            {CHtml::error("skill_id", $object)}
        </div>
    </div>
	
	<div class="control-group">
	    {CHtml::activeLabel("type_task", $object)}
	    <div class="controls">
	        {CHtml::activeTextBox("type_task", $object)}
	        {CHtml::error("type_task", $object)}
	    </div>
	</div>
		
	<div class="control-group">
	    {CHtml::activeLabel("procedure_eval", $object)}
	    <div class="controls">
	        {CHtml::activeTextBox("procedure_eval", $object)}
	        {CHtml::error("procedure_eval", $object)}
	    </div>
	</div>
		
	<div class="control-group">
	    {CHtml::activeLabel("criteria_eval", $object)}
	    <div class="controls">
	        {CHtml::activeTextBox("criteria_eval", $object)}
	        {CHtml::error("criteria_eval", $object)}
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
</form>