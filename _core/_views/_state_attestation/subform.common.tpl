<p>
    {CHtml::activeLabel("commission[title]", $form)}
    {CHtml::activeTextField("commission[title]", $form)}
    {CHtml::error("commission[title]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[comment]", $form)}
    {CHtml::activeTextBox("commission[comment]", $form)}
    {CHtml::error("commission[comment]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[year_id]", $form)}
    {CHtml::activeDropDownList("commission[year_id]", $form, CTaxonomyManager::getYearsList())}
    {CHtml::error("commission[year_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[secretar_id]", $form)}
    {CHtml::activeDropDownList("commission[secretar_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("commission[secretar_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[order_id]", $form)}
    {CHtml::activeDropDownList("commission[order_id]", $form, CStaffManager::getUsatuSEBOrdersList())}
    {CHtml::error("commission[order_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[manager_id]", $form)}
    {CHtml::activeDropDownList("commission[manager_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("commission[manager_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("commission[members]", $form)}
    {CHtml::activeMultiSelect("commission[members]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("commission[members]", $form)}
</p>