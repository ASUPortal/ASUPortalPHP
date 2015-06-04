<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $biography)}
	{CHtml::activeHiddenField("user_id", $biography)}
    
    <div class="control-group">
        {CHtml::activeLabel("main_text", $biography)}
        <div class="controls" style="width: 200%">
            {CHtml::activeTextBox("main_text", $biography)}
            {CHtml::error("main_text", $biography)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("image", $biography)}
        <div class="controls">
        	{CHtml::activeAttachPreview("image", $biography, 100)}
	        {CHtml::activeUpload("image", $biography)}
	        {CHtml::error("image", $biography)}
    	</div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>