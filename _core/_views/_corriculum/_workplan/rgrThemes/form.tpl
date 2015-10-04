<form action="workplanrgrthemes.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("rgr_title", $object)}
    <div class="controls">
        {CHtml::activeTextBox("rgr_title", $object)}
        {CHtml::error("rgr_title", $object)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>