<div class="control-group">
    {CHtml::activeLabel("grant[members]", $form)}
    <div class="controls">
    {CHtml::activeLookup("grant[members]", $form, "staff", true)}
    {CHtml::error("grant[members]", $form)}
</div></div>