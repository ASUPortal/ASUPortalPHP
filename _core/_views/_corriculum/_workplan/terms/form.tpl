<form action="workplanterms.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("number", $object)}
        <div class="controls">
        	{CHtml::activeLookup("number", $object, "class.CSearchCatalogCorriculumDisciplinesTerms", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("number", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>