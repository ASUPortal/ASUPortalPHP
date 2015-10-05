<form action="workplanwayofestimation.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}
  
    <div class="control-group">
        {CHtml::activeLabel("type_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("type_id", $object, "corriculum_way_of_estimation", false, array(), true)}
            {CHtml::error("type_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("criteria", $object)}
        <div class="controls">
            {CHtml::activeLookup("criteria", $object, "corriculum_criteria_of_estimation", true, array(), true)}
            {CHtml::error("criteria", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>