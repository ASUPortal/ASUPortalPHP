<form action="workplantechnologytermloads.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("type_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("technology_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("technology_id", $object, "corriculum_education_technologies")}
            {CHtml::error("technology_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("value", $object)}
        <div class="controls">
            {CHtml::activeTextField("value", $object)}
            {CHtml::error("value", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>