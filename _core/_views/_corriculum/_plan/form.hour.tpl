<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveHour")}
    {CHtml::activeHiddenField("discipline_id", $hour)}

    <p>
        {CHtml::activeLabel("period", $hour)}
        {CHtml::activeDropDownList("period", $hour, array(1, 2, 3, 4, 5, 6, 7, 8, 9))}
        {CHtml::error("period", $hour)}
    </p>

    <p>
        {CHtml::activeLabel("value", $hour)}
        {CHtml::activeTextField("value", $hour)}
        {CHtml::error("value", $hour)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>