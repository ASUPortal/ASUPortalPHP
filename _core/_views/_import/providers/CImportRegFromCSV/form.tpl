<div class="control-group">
    {CHtml::activeLabel("file", $form)}
    <div class="controls">
        {CHtml::activeUpload("file", $form)}
        {CHtml::error("file", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("created", $form)}
    <div class="controls">
        {CHtml::activeDateField("created", $form)}
        {CHtml::error("created", $form)}
    </div>
</div>