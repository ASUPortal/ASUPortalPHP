<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveDiscipline")}
    {CHtml::hiddenField("cycle_id", $cycle->id)}
    {CHtml::hiddenField("type", $type)}

    <p>
        {CHtml::label("Наименование дисциплины", "discipline_id")}
        {CHtml::dropDownList("discipline_id", CTaxonomyManager::getDisciplinesList())}
    </p>

    <p>
        {CHtml::label("Номер дисциплины", "number")}
        {CHtml::textField("number")}
    </p>

    <p>
        {CHtml::label("Родительская дисциплина", "parent_id")}
        {CHtml::dropDownList("parent_id", $cycle->getDisciplinesList())}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>