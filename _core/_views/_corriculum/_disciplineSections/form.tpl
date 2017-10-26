<form action="disciplineSections.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("discipline_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("title", $object)}
    <div class="controls">
        {CHtml::activeTextField("title", $object)}
        {CHtml::error("title", $object)}
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
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>