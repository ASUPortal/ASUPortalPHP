<form action="workplanintermediatecontrol.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("control_type_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("control_type_id", $object, "corriculum_labor_types")}
            {CHtml::error("control_type_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("term_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("term_id", $object, "class.CSearchCatalogWorkPlanTerms", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("term_id", $object)}
        </div>
    </div>
    
	<div class="control-group">
		{CHtml::activeLabel("ordering", $object)}
		<div class="controls">
			{CHtml::activeTextField("ordering", $object)}
			{CHtml::error("ordering", $object)}
		</div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>