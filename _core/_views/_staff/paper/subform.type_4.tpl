<div class="control-group">
    {CHtml::activeLabel("tema", $object)}
    <div class="controls">
        {CHtml::activeTextBox("tema", $object)}
        {CHtml::error("tema", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $object)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $object)}
        {CHtml::error("comment", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("file_attach", $object)}
    <div class="controls">
        {CHtml::activeUpload("file_attach", $object)}
        {CHtml::error("file_attach", $object)}
    </div>
</div>