<p>
    {CHtml::activeLabel("person[types]", $form)}
    {CHtml::activeCheckBoxGroup("person[types]", $form, CTaxonomyManager::getTypesList())}
    {CHtml::error("person[types]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[manager_id]", $form)}
    {CHtml::activeDropDownList("person[manager_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("person[manager_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[department_role_id]", $form)}
    {CHtml::activeDropDownList("person[department_role_id]", $form, CTaxonomyManager::getTaxonomy("department_roles")->getTermsList())}
    {CHtml::error("person[department_role_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[fio]", $form)}
    {CHtml::activeTextField("person[fio]", $form)}
    {CHtml::error("person[fio]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[fio_short]", $form)}
    {CHtml::activeTextField("person[fio_short]", $form)}
    {CHtml::error("person[fio_short]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[pol]", $form)}
    {CHtml::activeDropDownList("person[pol]", $form, CTaxonomyManager::getLegacyTaxonomy("pol")->getTermsList())}
    {CHtml::error("person[pol]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[date_rogd]", $form)}
    {CHtml::activeTextField("person[date_rogd]", $form, "date_rorg")}
    {CHtml::error("person[date_rogd]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[birth_place]", $form)}
    {CHtml::activeTextField("person[birth_place]", $form)}
    {CHtml::error("person[birth_place]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[nation]", $form)}
    {CHtml::activeTextField("person[nation]", $form)}
    {CHtml::error("person[nation]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[social]", $form)}
    {CHtml::activeTextField("person[social]", $form)}
    {CHtml::error("person[social]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[family_status]", $form)}
    {CHtml::activeDropDownList("person[family_status]", $form, CTaxonomyManager::getLegacyTaxonomy("family_status")->getTermsList())}
    {CHtml::error("person[family_status]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[INN]", $form)}
    {CHtml::activeTextField("person[INN]", $form)}
    {CHtml::error("person[INN]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[insurance_num]", $form)}
    {CHtml::activeTextField("person[insurance_num]", $form)}
    {CHtml::error("person[insurance_num]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[passp_seria]", $form)}
    {CHtml::activeTextField("person[passp_seria]", $form)}
    {CHtml::error("person[passp_seria]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[passp_nomer]", $form)}
    {CHtml::activeTextField("person[passp_nomer]", $form)}
    {CHtml::error("person[passp_nomer]", $form)}
</p>

<p>
    {CHtml::activeLabel("person[passp_place]", $form)}
    {CHtml::activeTextBox("person[passp_place]", $form)}
    {CHtml::error("person[passp_place]", $form)}
</p>