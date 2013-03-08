<form action="groups.php" method="post" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $group)}

    <p>{CHtml::errorSummary($group)}</p>

    <p>
        {CHtml::activeLabel("name", $group)}
        {CHtml::activeTextField("name", $group)}
        {CHtml::error("name", $group)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $group)}
        {CHtml::activeTextBox("comment", $group)}
        {CHtml::error("comment", $group)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>