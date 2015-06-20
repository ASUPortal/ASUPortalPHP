<form action="workplanbrs.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("mark_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("mark_id", $object, "study_marks")}
            {CHtml::error("mark_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("range", $object)}
        <div class="controls">
            {CHtml::activeTextField("range", $object)}
            {CHtml::error("range", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("is_ok", $object)}
        <div class="controls">
            {CHtml::activeCheckbox("is_ok", $object)}
            {CHtml::error("is_ok", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>