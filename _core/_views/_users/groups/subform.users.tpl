<p id="roles">
    {CHtml::activeCheckBoxGroup("group[members]", $form, CStaffManager::getAllUsersList())}
    {CHtml::error("group[members]", $form)}
</p>