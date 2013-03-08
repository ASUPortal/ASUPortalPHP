<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveTaxonomy")}

    <p>
        {CHtml::label("Название таксономии", "name")}
        {CHtml::textField("name")}
    </p>

    <p>
        {CHtml::label("Псевдоним таксономии", "alias")}
        {CHtml::textField("alias")}
    </p>

    <p>
        {CHtml::label("Термины (через точку с запятой)", "terms")}
        {CHtml::textBox("terms")}
    </p>

        <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>