<form action="workplantechnologyterms.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("term_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("term_id", $object, "class.CSearchCatalogWorkPlanTerms", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("term_id", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>