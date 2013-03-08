<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveCycle")}
    {CHtml::hiddenField("corriculum_id", $corriculum->id)}

    <p>
        {CHtml::label("Название цикла", "title")}
        {CHtml::textField("title")}
    </p>

    <p>
        {CHtml::label("Номер цикла", "number")}
        {CHtml::textField("number")}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>