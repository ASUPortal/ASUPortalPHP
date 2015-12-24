<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $discipline)}
    
    <div class="control-group">
        {CHtml::activeLabel("name", $discipline)}
        <div class="controls">
            {CHtml::activeTextBox("name", $discipline)}
            {CHtml::error("name", $discipline)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("library_code", $discipline)}
        <div class="controls">
            {CHtml::activeTextField("library_code", $discipline)}
            {CHtml::error("library_code", $discipline)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>