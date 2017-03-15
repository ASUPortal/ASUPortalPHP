<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
	{CHtml::hiddenField("action", "save")}
	{CHtml::activeHiddenField("id", $protocol)}

    <p>{CHtml::errorSummary($protocol)}</p>
    
	<div class="control-group">
        {CHtml::activeLabel("date_text", $protocol)}
        <div class="controls">
            {CHtml::activeDateField("date_text", $protocol)}
            {CHtml::error("date_text", $protocol)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("num", $protocol)}
        <div class="controls">
            {CHtml::activeTextField("num", $protocol)}
            {CHtml::error("num", $protocol)}
        </div>
	</div>

	<div class="control-group">
        {CHtml::activeLabel("program_content", $protocol)}
        <div class="controls">
            {CHtml::activeTextBox("program_content", $protocol)}
            {CHtml::error("program_content", $protocol)}
        </div>
	</div>

	<div class="control-group">
        {CHtml::activeLabel("comment", $protocol)}
		<div class="controls">
            {CHtml::activeTextBox("comment", $protocol)}
            {CHtml::error("comment", $protocol)}
		</div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>