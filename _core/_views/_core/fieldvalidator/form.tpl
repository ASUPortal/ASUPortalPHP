<form action="fieldvalidators.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $validator)}
    {CHtml::activeHiddenField("field_id", $validator)}

    {CHtml::errorSummary($validator)}

    <div class="control-group">
        {CHtml::activeLabel("validator_id", $validator)}
        <div class="controls">
            {CHtml::activeDropDownList("validator_id", $validator, CCoreObjectsManager::getCoreValidatorsList(1))}
            {CHtml::error("validator_id", $validator)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>