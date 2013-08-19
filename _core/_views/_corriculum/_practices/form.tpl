<form action="practices.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $practice)}
    {CHtml::activeHiddenField("corriculum_id", $practice)}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $practice)}
        <div class="controls">
        {CHtml::activeDropDownList("type_id", $practice, CTaxonomyManager::getTaxonomy("practice_types")->getTermsList())}
        {CHtml::error("type_id", $practice)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $practice)}
        <div class="controls">
        {CHtml::activeDropDownList("discipline_id", $practice, CTaxonomyManager::getDisciplinesList())}
        {CHtml::error("discipline_id", $practice)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("alias", $practice)}
        <div class="controls">
        {CHtml::activeTextField("alias", $practice)}
        {CHtml::error("alias", $practice)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("length", $practice)}
        <div class="controls">
        {CHtml::activeTextField("length", $practice)}
        {CHtml::error("length", $practice)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>