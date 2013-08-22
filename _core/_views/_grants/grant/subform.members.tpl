<div class="control-group">
    {CHtml::activeLabel("grant[members]", $form)}
    <div class="controls">
    {CHtml::activeMultiSelect("grant[members]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("grant[members]", $form)}
</div></div>