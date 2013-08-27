<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "saveTaxonomy")}
    {CHtml::activeHiddenField("id", $taxonomy)}

    <div class="control-group">
        {CHtml::activeLabel("name", $taxonomy)}
        <div class="controls">
        {CHtml::activeTextField("name", $taxonomy)}
        {CHtml::error("name", $taxonomy)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("alias", $taxonomy)}
        <div class="controls">
        {CHtml::activeTextField("alias", $taxonomy)}
        {CHtml::error("alias", $taxonomy)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("terms", $taxonomy)}
        <div class="controls">
        {CHtml::activeTextBox("terms", $taxonomy)}
        {CHtml::error("terms", $taxonomy)}
    </div></div>

        <div class="control-group">
            <div class="controls">
            {CHtml::submit("Сохранить")}
    </div></div>
</form>