<form action="competentions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("discipline_id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("competention_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("competention_id", $object, "corriculum_competentions")}
            {CHtml::error("competention_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("knowledge_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("knowledge_id", $object, "corriculum_knowledges")}
            {CHtml::error("knowledge_id", $object)}
        </div>
    </div>


    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>