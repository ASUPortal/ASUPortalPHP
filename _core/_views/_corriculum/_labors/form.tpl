<form action="labors.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $labor)}
    {CHtml::activeHiddenField("discipline_id", $labor)}

    <p>
        {CHtml::activeLabel("type_id", $labor)}
        {CHtml::activeDropDownList("type_id", $labor, CTaxonomyManager::getTaxonomy("corriculum_labor_types")->getTermsList())}
        {CHtml::error("type_id", $labor)}
    </p>

    <p>
        {CHtml::activeLabel("value", $labor)}
        {CHtml::activeTextField("value", $labor)}
        {CHtml::error("value", $labor)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>