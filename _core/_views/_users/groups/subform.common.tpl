<div class="control-group">
    {CHtml::activeLabel("group[comment]", $form)}
    <div class="controls">
    {CHtml::activeTextField("group[comment]", $form)}
    {CHtml::error("group[comment]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("group[name]", $form)}
    <div class="controls">
    {CHtml::activeTextField("group[name]", $form)}
    {CHtml::error("group[name]", $form)}
    </div>
</div>