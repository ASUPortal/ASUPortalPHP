<p>
    {CHtml::activeLabel("grant[members]", $form)}
    {CHtml::activeMultiSelect("grant[members]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("grant[members]", $form)}
</p>