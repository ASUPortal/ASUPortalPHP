<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "saveFile")}
    {CHtml::activeHiddenField("id", $file)}
    {CHtml::activeHiddenField("user_id", $file)}
	{CHtml::activeHiddenField("nameFolder", $file)}
	{CHtml::activeHiddenField("date_time", $file)}
    
	<div class="control-group">
        {CHtml::activeLabel("browserFile", $file)}
        <div class="controls">
            {CHtml::activeTextField("browserFile", $file)}
            {CHtml::error("browserFile", $file)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("nameFile", $file)}
        <div class="controls">
	        {CHtml::activeUpload("nameFile", $file)}
	        {CHtml::error("nameFile", $file)}
    	</div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("add_link", $file)}
        <div class="controls">
            {CHtml::activeTextBox("add_link", $file)}
            {CHtml::error("add_link", $file)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>