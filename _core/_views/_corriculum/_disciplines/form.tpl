<form action="disciplines.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $discipline)}
    {CHtml::activeHiddenField("cycle_id", $discipline)}

    <p>
        {CHtml::activeLabel("discipline_id", $discipline)}
        {CHtml::activeDropDownList("discipline_id", $discipline, CTaxonomyManager::getDisciplinesList())}
        {CHtml::error("discipline_id", $discipline)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>