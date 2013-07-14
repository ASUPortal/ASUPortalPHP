<form action="index.php" class="form-horizontal">
<input type="hidden" name="action" value="save">
{CHtml::activeHiddenField("id", $question)}
    <div class="control-group">
        {CHtml::activeLabel("speciality_id", $question)}
        <div class="controls">
            {CHtml::activeDropDownList("speciality_id", $question, CTaxonomyManager::getSpecialitiesList())}
            {CHtml::error("speciality_id", $question)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("course", $question)}
        <div class="controls">
            {CHtml::activeDropDownList("course", $question, $cources)}
            {CHtml::error("course", $question)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("year_id", $question)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $question, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $question)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("category_id", $question)}
        <div class="controls">
            {CHtml::activeDropDownList("category_id", $question, CTaxonomyManager::getTaxonomy("questions_types")->getTermsList())}
            {CHtml::error("category_id", $question)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $question)}
        <div class="controls">
            {CHtml::activeDropDownList("discipline_id", $question, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("discipline_id", $question)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text", $question)}
        <div class="controls">
            {CHtml::activeTextBox("text", $question)}
            {CHtml::error("text", $question)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>