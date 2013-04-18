<p id="roles">
    {CHtml::activeCheckBoxGroup("grant[members]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("grant[members]", $form)}
</p>