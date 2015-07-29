<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "saveDocument")}
    {CHtml::activeHiddenField("id", $document)}
	{CHtml::activeHiddenField("user_id", $document)}
	{CHtml::activeHiddenField("nameFolder", $document)}
    
	<div class="control-group">
	    {CHtml::activeLabel("subj_id", $document)}
	    <div class="controls">
		{CHtml::activeDropDownList("subj_id", $document, $disciplines)}
	    {CHtml::error("subj_id", $document)}
	    </div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>