<form action="index.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $grant)}
    {CHtml::hiddenField("action", "save")}

    <p>
        {CHtml::activeLabel("title", $grant)}
        {CHtml::activeTextField("title", $grant)}
        {CHtml::error("title", $grant)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $grant)}
        {CHtml::activeTextBox("comment", $grant)}
        {CHtml::error("comment", $grant)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>