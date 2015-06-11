<form action="workplancompetentions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
    {CHtml::activeLabel("id", $object)}
    <div class="controls">
        {CHtml::activeTextField("id", $object)}
        {CHtml::error("id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_id", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_id", $object)}
        {CHtml::error("plan_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("competention_id", $object)}
    <div class="controls">
        {CHtml::activeTextField("competention_id", $object)}
        {CHtml::error("competention_id", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>