<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $quest)}
    {CHtml::activeHiddenField("user_id", $quest)}
    
    <div class="control-group">
        {CHtml::activeLabel("user_id", $quest)}
        <div class="controls">
             {CHtml::activeDropDownList("user_id", $quest, $users)}
             {CHtml::error("user_id", $quest)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("question_text", $quest)}
        <div class="controls">
            {CHtml::activeTextBox("question_text", $quest)}
            {CHtml::error("question_text", $quest)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("contact_info", $quest)}
        <div class="controls">
            {CHtml::activeTextBox("contact_info", $quest)}
            {CHtml::error("contact_info", $quest)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>