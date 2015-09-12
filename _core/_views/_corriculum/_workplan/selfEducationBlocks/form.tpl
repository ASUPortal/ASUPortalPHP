<form action="workplanselfeducationblocks.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("load_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("question_title", $object)}
        <div class="controls">
            {CHtml::activeTextBox("question_title", $object)}
            {CHtml::error("question_title", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("question_hours", $object)}
        <div class="controls">
            {CHtml::activeTextField("question_hours", $object)}
            {CHtml::error("question_hours", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>