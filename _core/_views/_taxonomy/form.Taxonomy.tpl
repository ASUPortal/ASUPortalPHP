<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveTaxonomy")}
    {CHtml::activeHiddenField("id", $taxonomy)}

    <p>
        {CHtml::activeLabel("name", $taxonomy)}
        {CHtml::activeTextField("name", $taxonomy)}
        {CHtml::error("name", $taxonomy)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $taxonomy)}
        {CHtml::activeTextField("alias", $taxonomy)}
        {CHtml::error("alias", $taxonomy)}
    </p>

    <p>
        {CHtml::activeLabel("terms", $taxonomy)}
        {CHtml::activeTextBox("terms", $taxonomy)}
        {CHtml::error("terms", $taxonomy)}
    </p>

        <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>