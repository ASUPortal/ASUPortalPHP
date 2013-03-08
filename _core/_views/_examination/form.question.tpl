<form action="index.php">
<input type="hidden" name="action" value="save">
{CHtml::activeHiddenField("id", $question)}
    <p>
        {CHtml::activeLabel("speciality_id", $question)}
        {CHtml::activeDropDownList("speciality_id", $question, CTaxonomyManager::getSpecialitiesList())}
        {CHtml::error("speciality_id", $question)}
    </p>

    <p>
        {CHtml::activeLabel("course", $question)}
        {CHtml::activeDropDownList("course", $question, $cources)}
        {CHtml::error("course", $question)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $question)}
        {CHtml::activeDropDownList("year_id", $question, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $question)}
    </p>

    <p>
        {CHtml::activeLabel("category_id", $question)}
        {CHtml::activeDropDownList("category_id", $question, CTaxonomyManager::getTaxonomy("questions_types")->getTermsList())}
        {CHtml::error("category_id", $question)}
    </p>

    <p>
        {CHtml::activeLabel("discipline_id", $question)}
        {CHtml::activeDropDownList("discipline_id", $question, CTaxonomyManager::getDisciplinesList())}
        {CHtml::error("discipline_id", $question)}
    </p>

    <p>
        {CHtml::activeLabel("text", $question)}
        {CHtml::activeTextBox("text", $question)}
        {CHtml::error("text", $question)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>