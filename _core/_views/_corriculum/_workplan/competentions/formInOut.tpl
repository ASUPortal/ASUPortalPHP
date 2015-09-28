<form action="workplancompetentions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("type", $object)}
    {CHtml::activeHiddenField("allow_delete", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("competention_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("competention_id", $object, "corriculum_competentions", false, array(), true)}
            {CHtml::error("competention_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("level_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("level_id", $object, "corriculum_level_of_development", false, array(), true)}
            {CHtml::error("level_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("discipline_id", $object, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("discipline_id", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
