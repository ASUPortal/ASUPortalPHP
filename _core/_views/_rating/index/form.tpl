<form action="indexes.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $index)}

    {CHtml::errorSummary($index)}

    <div class="control-group">
        {CHtml::activeLabel("title", $index)}
        <div class="controls">
        {CHtml::activeTextField("title", $index)}
        {CHtml::error("title", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("unit", $index)}
        <div class="controls">
        {CHtml::activeTextField("unit", $index)}
        {CHtml::error("unit", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("weight", $index)}
        <div class="controls">
        {CHtml::activeTextField("weight", $index)}
        {CHtml::error("weight", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("class", $index)}
        <div class="controls">
        {CHtml::activeTextField("class", $index)}
        {CHtml::error("class", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("value_planned", $index)}
        <div class="controls">
        {CHtml::activeTextField("value_planned", $index)}
        {CHtml::error("value_planned", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("value_fact", $index)}
        <div class="controls">
        {CHtml::activeTextField("value_fact", $index)}
        {CHtml::error("value_fact", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("year_id", $index)}
        <div class="controls">
        {CHtml::activeDropDownList("year_id", $index, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $index)}
    </div></div>

    <div class="control-group">
        <div class="controls">
    {CHtml::submit("Сохранить")}
    </div></div>
</form>