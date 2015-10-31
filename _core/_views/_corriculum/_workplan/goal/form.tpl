<form action="workplangoals.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("goal", $object)}
    <div class="controls">
        {CHtml::activeTextBox("goal", $object)}
        {CHtml::error("goal", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>