<p>
    {CHtml::activeLabel("person[order_seb_id]", $form)}
    {CHtml::activeDropDownList("person[order_seb_id]", $form, CStaffManager::getUsatuSEBOrdersList())}
    {CHtml::error("person[order_seb_id]", $form)}
</p>