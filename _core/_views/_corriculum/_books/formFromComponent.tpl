<form action="books.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("subject_id", $param)}
    {CHtml::activeHiddenField("book_id", $param)}
    {CHtml::hiddenField("plan_id", $plan_id)}
    {CHtml::hiddenField("type", $type)}
    
    {CHtml::errorSummary($object)}
    
    <div class="control-group">
        {CHtml::activeLabel("book_name", $object)}
        <div class="controls">
            {CHtml::activeTextBox("book_name", $object)}
            {CHtml::error("book_name", $object)}
        </div>
    </div>
        
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>
