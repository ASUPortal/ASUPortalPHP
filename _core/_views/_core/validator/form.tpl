<form action="validators.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $validator)}

    {CHtml::errorSummary($validator)}

    <div class="control-group">
        {CHtml::activeLabel("title", $validator)}
        <div class="controls">
            {CHtml::activeTextField("title", $validator)}
            {CHtml::error("title", $validator)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("class_name", $validator)}
        <div class="controls">
            {CHtml::activeTextField("class_name", $validator)}
            {CHtml::error("class_name", $validator)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("type_id", $validator)}
        <div class="controls">
            {CHtml::activeDropDownList("type_id", $validator, CCoreValidatorsController::getTypesList())}
            {CHtml::error("type_id", $validator)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $validator)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $validator)}
            {CHtml::error("comment", $validator)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>