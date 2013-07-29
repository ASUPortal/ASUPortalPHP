<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "saveLabor")}
    {CHtml::hiddenField("discipline_id", $discipline->id)}

    <div class="control-group">
        {CHtml::label("Тип трудоемоксти", "type_id")}
        {CHtml::dropDownList("type_id", CTaxonomyManager::getTaxonomy("corriculum_labor_types")->getTermsList())}
    </div>

    <div class="control-group">
        {CHtml::label("Вид трудоемоксти", "form_id")}
        {CHtml::dropDownList("form_id", CTaxonomyManager::getTaxonomy("corriculum_labor_form")->getTermsList())}
    </div>

    <div class="control-group">
        {CHtml::label("Значение", "value")}
        {CHtml::textField("value")}
    </div>

    <div class="control-group">
         <div class="controls">
            {CHtml::submit("Сохранить")}
         </div>
    </div>
</form>