<form action="fields.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $field)}
    {CHtml::activeHiddenField("model_id", $field)}

    {CHtml::errorSummary($field)}

    <div class="control-group">
        {CHtml::activeLabel("field_name", $field)}
        <div class="controls">
            {CHtml::activeTextField("field_name", $field)}
            {CHtml::error("field_name", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("export_to_search", $field)}
        <div class="controls">
            {CHtml::activeCheckBox("export_to_search", $field)}
            {CHtml::error("export_to_search", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $field)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $field)}
            {CHtml::error("comment", $field)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>