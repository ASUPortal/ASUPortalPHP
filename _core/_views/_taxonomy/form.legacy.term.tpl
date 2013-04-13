<form action="index.php" method="post">
    {CHtml::activeHiddenField("id", $term)}
    {CHtml::activeHiddenField("taxonomy_id", $term)}
    {CHtml::hiddenField("action", "saveLegacyTerm")}

    <p>
        {CHtml::activeLabel("name", $term)}
        {CHtml::activeTextField("name", $term)}
        {CHtml::error("name", $term)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>