<div class="control-group">
    <div class="controls">
    {CHtml::activeCheckBoxGroup("group[members]", $form, CStaffManager::getAllUsersList())}
    {CHtml::error("group[members]", $form)}
    </div>
</div>