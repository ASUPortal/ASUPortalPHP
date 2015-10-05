<form action="workplanprojectthemes.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("type", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("project_title", $object)}
    <div class="controls">
        {CHtml::activeTextBox("project_title", $object)}
        {CHtml::error("project_title", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>