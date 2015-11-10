<form action="workplanevaluationmaterials.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("type_id", $object, "corriculum_type_estimated_material", false, array(), true)}
            {CHtml::error("type_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("material", $object)}
        <div class="controls">
            {CHtml::activeTextBox("material", $object)}
            {CHtml::error("material", $object)}
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