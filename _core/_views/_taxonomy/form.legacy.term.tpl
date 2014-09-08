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
        {CHtml::activeLabel("name_short", $term)}
        {CHtml::activeTextField("name_short", $term)}
        {CHtml::error("name_short", $term)}
    </p>
    <p>
        {CHtml::activeLabel("comment", $term)}
        {CHtml::activeTextBox("comment", $term)}
        {CHtml::error("comment", $term)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
