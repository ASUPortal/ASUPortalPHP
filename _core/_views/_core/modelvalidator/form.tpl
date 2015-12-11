<form action="modelvalidators.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("model_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("validator_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("validator_id", $object, CCoreObjectsManager::getCoreValidatorsList(array(2, 3)))}
            {CHtml::error("validator_id", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>