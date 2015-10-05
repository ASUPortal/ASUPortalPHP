<form action="workplantypescontrol.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("type_study_activity_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("type_study_activity_id", $object, "corriculum_types_study_activity", false, array(), true)}
            {CHtml::error("type_study_activity_id", $object)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("section_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("section_id", $object, "class.CSearchCatalogWorkPlanSections", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("section_id", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("control_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("control_id", $object, "corriculum_control_type", false, array(), true)}
            {CHtml::error("control_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("mark", $object)}
        <div class="controls">
            {CHtml::activeTextField("mark", $object)}
            {CHtml::error("mark", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("amount_labors", $object)}
        <div class="controls">
            {CHtml::activeTextField("amount_labors", $object)}
            {CHtml::error("amount_labors", $object)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("min", $object)}
        <div class="controls">
            {CHtml::activeTextField("min", $object)}
            {CHtml::error("min", $object)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("max", $object)}
        <div class="controls">
            {CHtml::activeTextField("max", $object)}
            {CHtml::error("max", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>