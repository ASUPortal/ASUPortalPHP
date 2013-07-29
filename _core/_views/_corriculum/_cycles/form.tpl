<form action="cycles.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $cycle)}
    {CHtml::activeHiddenField("corriculum_id", $cycle)}

    <div class="control-group">
        {CHtml::activeLabel("title", $cycle)}
        <div class="controls">
            {CHtml::activeTextField("title", $cycle)}
            {CHtml::error("title", $cycle)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("title_abbreviated", $cycle)}
        <div class="controls">
            {CHtml::activeTextField("title_abbreviated", $cycle)}
            {CHtml::error("title_abbreviated", $cycle)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("number", $cycle)}
        <div class="controls">
            {CHtml::activeTextField("number", $cycle)}
            {CHtml::error("number", $cycle)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>