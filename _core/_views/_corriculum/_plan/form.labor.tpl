<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveLabor")}
    {CHtml::hiddenField("discipline_id", $discipline->id)}

    <p>
        {CHtml::label("Тип трудоемоксти", "type_id")}
        {CHtml::dropDownList("type_id", CTaxonomyManager::getTaxonomy("corriculum_labor_types")->getTermsList())}
    </p>

    <p>
        {CHtml::label("Вид трудоемоксти", "form_id")}
        {CHtml::dropDownList("form_id", CTaxonomyManager::getTaxonomy("corriculum_labor_form")->getTermsList())}
    </p>

    <p>
        {CHtml::label("Значение", "value")}
        {CHtml::textField("value")}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>