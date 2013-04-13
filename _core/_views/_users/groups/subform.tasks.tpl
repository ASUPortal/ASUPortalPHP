<p id="roles">
    {CHtml::activeCheckBoxGroup("group[roles]", $form, CStaffManager::getAllUserRolesList())}
    {CHtml::error("group[roles]", $form)}
</p>