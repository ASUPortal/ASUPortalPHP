<form action="workplanfundmarktypes.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("section_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("section_id", $object, "class.CSearchCatalogWorkPlanSections", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("section_id", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("competentions", $object)}
        <div class="controls">
            {CHtml::activeLookup("competentions", $object, "class.CSearchCatalogWorkPlanCompetentions", true, ["plan_id" => $object->plan_id])}
            {CHtml::error("competentions", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("levels", $object)}
        <div class="controls">
            {CHtml::activeLookup("levels", $object, "corriculum_level_of_development", true, array())}
            {CHtml::error("levels", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("controls", $object)}
        <div class="controls">
            {CHtml::activeLookup("controls", $object, "corriculum_control_form", true, array())}
            {CHtml::error("controls", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>