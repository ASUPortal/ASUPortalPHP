<form action="workplancontenttopics.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("load_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("title", $object)}
        <div class="controls">
            {CHtml::activeTextBox("title", $object)}
            {CHtml::error("title", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("value", $object)}
        <div class="controls">
            {CHtml::activeTextField("value", $object)}
            {CHtml::error("value", $object)}
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