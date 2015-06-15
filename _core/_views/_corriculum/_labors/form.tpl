<form action="labors.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $labor)}
    {CHtml::activeHiddenField("section_id", $labor)}
    {CHtml::activeHiddenField("discipline_id", $labor)}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $labor)}
        <div class="controls">
            {CHtml::activeDropDownList("type_id", $labor, CTaxonomyManager::getTaxonomy("corriculum_labor_types")->getTermsList())}
            {CHtml::error("type_id", $labor)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("value", $labor)}
        <div class="controls">
            {CHtml::activeTextField("value", $labor)}
            {CHtml::error("value", $labor)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>