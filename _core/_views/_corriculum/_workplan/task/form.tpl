<form action="workplantasks.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("goal_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("task", $object)}
    <div class="controls">
        {CHtml::activeTextBox("task", $object)}
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
</form>