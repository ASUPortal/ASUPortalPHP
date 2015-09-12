<form action="workplancompetentions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("allow_delete", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("competention_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("competention_id", $object, "corriculum_competentions")}
            {CHtml::error("competention_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("knowledges", $object)}
        <div class="controls">
            {CHtml::activeLookup("knowledges", $object, "corriculum_knowledges", true)}
            {CHtml::error("knowledges", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("skills", $object)}
        <div class="controls">
            {CHtml::activeLookup("skills", $object, "corriculum_knowledges", true)}
            {CHtml::error("skills", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("experiences", $object)}
        <div class="controls">
            {CHtml::activeLookup("experiences", $object, "corriculum_knowledges", true)}
            {CHtml::error("experiences", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>