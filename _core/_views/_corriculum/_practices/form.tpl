<form action="practices.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $practice)}
    {CHtml::activeHiddenField("corriculum_id", $practice)}

    <p>
        {CHtml::activeLabel("type_id", $practice)}
        {CHtml::activeDropDownList("type_id", $practice, CTaxonomyManager::getTaxonomy("practice_types")->getTermsList())}
        {CHtml::error("type_id", $practice)}
    </p>

    <p>
        {CHtml::activeLabel("discipline_id", $practice)}
        {CHtml::activeDropDownList("discipline_id", $practice, CTaxonomyManager::getDisciplinesList())}
        {CHtml::error("discipline_id", $practice)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $practice)}
        {CHtml::activeTextField("alias", $practice)}
        {CHtml::error("alias", $practice)}
    </p>

    <p>
        {CHtml::activeLabel("length", $practice)}
        {CHtml::activeTextField("length", $practice)}
        {CHtml::error("length", $practice)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>