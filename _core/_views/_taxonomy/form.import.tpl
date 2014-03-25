<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "importTerms")}
    {CHtml::activeHiddenField("id", $taxonomy)}

    <div class="control-group">
        {CHtml::activeLabel("terms", $taxonomy)}
        <div class="controls">
        {CHtml::activeTextBox("terms", $taxonomy)}
        {CHtml::error("terms", $taxonomy)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Импортировать", false)}
        </div>
    </div>
</form>
