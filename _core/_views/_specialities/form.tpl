<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $speciality)}

    <p>{CHtml::errorSummary($speciality)}</p>

    <p>
        {CHtml::activeLabel("name", $speciality)}
        {CHtml::activeTextField("name", $speciality)}
        {CHtml::error("name", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $speciality)}
        {CHtml::activeTextBox("comment", $speciality)}
        {CHtml::error("comment", $speciality)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>