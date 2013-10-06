<form action="models.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $model)}

    {CHtml::errorSummary($model)}

    <div class="control-group">
        {CHtml::activeLabel("title", $model)}
        <div class="controls">
            {CHtml::activeTextField("title", $model)}
            {CHtml::error("title", $model)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("class_name", $model)}
        <div class="controls">
            {CHtml::activeTextField("class_name", $model)}
            {CHtml::error("class_name", $model)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("export_to_search", $model)}
        <div class="controls">
            {CHtml::activeCheckBox("export_to_search", $model)}
            {CHtml::error("export_to_search", $model)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $model)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $model)}
            {CHtml::error("comment", $model)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>