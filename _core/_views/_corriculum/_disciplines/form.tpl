<form action="disciplines.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $discipline)}
    {CHtml::activeHiddenField("cycle_id", $discipline)}

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $discipline)}
        <div class="controls">
            {CHtml::activeDropDownList("discipline_id", $discipline, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("discipline_id", $discipline)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("parent_id", $discipline)}
        <div class="controls">
            {CHtml::activeDropDownList("parent_id", $discipline, $cycle->getDisciplinesList())}
            {CHtml::error("parent_id", $discipline)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("ordering", $discipline)}
        <div class="controls">
            {CHtml::activeTextField("ordering", $discipline)}
            {CHtml::error("ordering", $discipline)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>