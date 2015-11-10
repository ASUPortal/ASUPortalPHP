<form action="workplanmarktypes.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("type_id", $object, "corriculum_control_type", false, array(), true)}
            {CHtml::error("type_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("form_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("form_id", $object, "corriculum_labor_form", false, array(), true)}
            {CHtml::error("form_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("funds", $object)}
        <div class="controls">
            {CHtml::activeLookup("funds", $object, "corriculum_control_funds", true, array(), true)}
            {CHtml::error("funds", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("places", $object)}
        <div class="controls">
            {CHtml::activeLookup("places", $object, "corriculum_marktype_place", true, array(), true)}
            {CHtml::error("places", $object)}
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