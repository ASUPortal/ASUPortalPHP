<div class="control-group" id="roles">
    <div class="controls">
    {CHtml::activeCheckBoxGroup("group[roles]", $form, CStaffManager::getAllUserRolesList())}
    {CHtml::error("group[roles]", $form)}
    </div>
</div>