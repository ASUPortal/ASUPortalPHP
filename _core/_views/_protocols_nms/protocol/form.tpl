<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("date_text", $object)}
        <div class="controls">
            {CHtml::activeDateField("date_text", $object)}
            {CHtml::error("date_text", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("num", $object)}
        <div class="controls">
            {CHtml::activeTextField("num", $object)}
            {CHtml::error("num", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("program_content", $object)}
        <div class="controls">
            {CHtml::activeTextBox("program_content", $object)}
            {CHtml::error("program_content", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("original", $object)}
        <div class="controls">
            {CHtml::activeUpload("original", $object)}
            {CHtml::error("original", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $object)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $object)}
            {CHtml::error("comment", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>