<form action="workplancontentsections.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("name", $object)}
        <div class="controls">
            {CHtml::activeTextBox("name", $object)}
            {CHtml::error("name", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("content", $object)}
        <div class="controls">
            {CHtml::activeTextBox("content", $object)}
            {CHtml::error("content", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("sectionIndex", $object)}
        <div class="controls">
            {CHtml::activeTextField("sectionIndex", $object)}
            {CHtml::error("sectionIndex", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("category_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("category_id", $object, "class.CSearchCatalogWorkPlanCategories", false, ["plan_id" => $object->category->plan_id])}
            {CHtml::error("category_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("controls", $object)}
        <div class="controls">
            {CHtml::activeLookup("controls", $object, "corriculum_control_form", true, array(), true)}
            {CHtml::error("controls", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("recommendedLiterature", $object)}
        <div class="controls">
            {CHtml::activeLookup("recommendedLiterature", $object, "class.CSearchCatalogWorkPlanLiterature", true, ["plan_id" => $object->category->plan_id])}
            {CHtml::error("recommendedLiterature", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>