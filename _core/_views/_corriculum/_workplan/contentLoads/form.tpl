<form action="workplancontentloads.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("section_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("load_type_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("load_type_id", $object, "corriculum_labor_types")}
            {CHtml::error("load_type_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("term_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("term_id", $object, "class.CSearchCatalogWorkPlanTerms", false, ["plan_id" => $object->section->category->plan_id])}
            {CHtml::error("term_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("value", $object)}
        <div class="controls">
            {CHtml::activeTextField("value", $object)}
            {CHtml::error("value", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>