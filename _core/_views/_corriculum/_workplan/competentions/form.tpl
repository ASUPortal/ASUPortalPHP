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

{if ($object->type) != 0}
    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("discipline_id", $object, "class.CSearchCatalogCorriculumDisciplines", false, ["plan_id" => $object->plan_id])}
            {CHtml::error("discipline_id", $object)}
        </div>
    </div>
    
{else}
    <div class="control-group">
        {CHtml::activeLabel("canUse", $object)}
        <div class="controls">
            {CHtml::activeLookup("canUse", $object, "corriculum_knowledges", true, array(), true)}
            {CHtml::error("canUse", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("type_task", $object)}
        <div class="controls">
            {CHtml::activeTextBox("type_task", $object)}
            {CHtml::error("type_task", $object)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("procedure_eval", $object)}
        <div class="controls">
            {CHtml::activeTextBox("procedure_eval", $object)}
            {CHtml::error("procedure_eval", $object)}
        </div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("criteria_eval", $object)}
        <div class="controls">
            {CHtml::activeTextBox("criteria_eval", $object)}
            {CHtml::error("criteria_eval", $object)}
        </div>
    </div>
{/if}

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>

</form>
