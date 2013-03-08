<form action="cycles.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $cycle)}
    {CHtml::activeHiddenField("corriculum_id", $cycle)}

    <p>
        {CHtml::activeLabel("title", $cycle)}
        {CHtml::activeTextField("title", $cycle)}
        {CHtml::error("title", $cycle)}
    </p>

    <p>
        {CHtml::activeLabel("title_abbreviated", $cycle)}
        {CHtml::activeTextField("title_abbreviated", $cycle)}
        {CHtml::error("title_abbreviated", $cycle)}
    </p>

    <p>
        {CHtml::activeLabel("number", $cycle)}
        {CHtml::activeTextField("number", $cycle)}
        {CHtml::error("number", $cycle)}
    </p>

        <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>