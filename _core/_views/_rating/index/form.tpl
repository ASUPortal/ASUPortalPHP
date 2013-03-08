<form action="indexes.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $index)}

    <p>{CHtml::errorSummary($index)}</p>

    <p>
        {CHtml::activeLabel("title", $index)}
        {CHtml::activeTextField("title", $index)}
        {CHtml::error("title", $index)}
    </p>

    <p>
        {CHtml::activeLabel("unit", $index)}
        {CHtml::activeTextField("unit", $index)}
        {CHtml::error("unit", $index)}
    </p>

    <p>
        {CHtml::activeLabel("weight", $index)}
        {CHtml::activeTextField("weight", $index)}
        {CHtml::error("weight", $index)}
    </p>

    <p>
        {CHtml::activeLabel("class", $index)}
        {CHtml::activeTextField("class", $index)}
        {CHtml::error("class", $index)}
    </p>

    <p>
        {CHtml::activeLabel("value_planned", $index)}
        {CHtml::activeTextField("value_planned", $index)}
        {CHtml::error("value_planned", $index)}
    </p>

    <p>
        {CHtml::activeLabel("value_fact", $index)}
        {CHtml::activeTextField("value_fact", $index)}
        {CHtml::error("value_fact", $index)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $index)}
        {CHtml::activeDropDownList("year_id", $index, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $index)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>