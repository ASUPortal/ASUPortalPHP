<form action="reports.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("settings_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("report_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("report_id", $object, "class.CReportsLookup")}
            {CHtml::error("report_id", $object)}
        </div>
    </div>
    

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>