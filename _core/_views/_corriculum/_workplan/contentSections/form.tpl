<form action="workplancontentsections.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("name", $object)}
    <div class="controls">
        {CHtml::activeTextBox("name", $object)}
        {CHtml::error("name", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("sectionIndex", $object)}
    <div class="controls">
        {CHtml::activeTextField("sectionIndex", $object)}
        {CHtml::error("sectionIndex", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>