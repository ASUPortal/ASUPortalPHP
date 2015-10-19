<form action="competentions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("discipline_id", $object)}

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

    <div class="control-group">
        {CHtml::activeLabel("knowledge_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("knowledge_id", $object, "corriculum_knowledges", false, array(), true)}
            {CHtml::error("knowledge_id", $object)}
        </div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("skill_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("skill_id", $object, "corriculum_knowledges", false, array(), true)}
            {CHtml::error("skill_id", $object)}
        </div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("experience_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("experience_id", $object, "corriculum_knowledges", false, array(), true)}
            {CHtml::error("experience_id", $object)}
        </div>
    </div>
        
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
