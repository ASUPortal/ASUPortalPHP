<div class="control-group">
    {CHtml::activeLabel("person[types]", $form)}
    <div class="controls">
    {CHtml::activeLookup("person[types][]", $form, "person_types", true)}
    {CHtml::error("person[types]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[to_tabel]", $form)}
    <div class="controls">
    {CHtml::activeCheckBox("person[to_tabel]", $form)}
    {CHtml::error("person[to_tabel]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[is_slave]", $form)}
    <div class="controls">
    {CHtml::activeCheckBox("person[is_slave]", $form)}
    {CHtml::error("person[is_slave]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[manager_id]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[manager_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("person[manager_id]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[department_role_id]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[department_role_id]", $form, CTaxonomyManager::getTaxonomy("department_roles")->getTermsList())}
    {CHtml::error("person[department_role_id]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[fio]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[fio]", $form)}
    {CHtml::error("person[fio]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[fio_short]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[fio_short]", $form)}
    {CHtml::error("person[fio_short]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[photo]", $form)}
    <div class="controls">
        {CHtml::activeUpload("person[photo]", $form)}
        {CHtml::error("person[photo]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[pol]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[pol]", $form, CTaxonomyManager::getLegacyTaxonomy("pol")->getTermsList())}
    {CHtml::error("person[pol]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[date_rogd]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[date_rogd]", $form, "date_rorg")}
    {CHtml::error("person[date_rogd]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[birth_place]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[birth_place]", $form)}
    {CHtml::error("person[birth_place]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[nation]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[nation]", $form)}
    {CHtml::error("person[nation]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[social]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[social]", $form)}
    {CHtml::error("person[social]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[family_status]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[family_status]", $form, CTaxonomyManager::getLegacyTaxonomy("family_status")->getTermsList())}
    {CHtml::error("person[family_status]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[INN]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[INN]", $form)}
    {CHtml::error("person[INN]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[insurance_num]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[insurance_num]", $form)}
    {CHtml::error("person[insurance_num]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[passp_seria]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[passp_seria]", $form)}
    {CHtml::error("person[passp_seria]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[passp_nomer]", $form)}
    <div class="controls">
    {CHtml::activeTextField("person[passp_nomer]", $form)}
    {CHtml::error("person[passp_nomer]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[passp_place]", $form)}
    <div class="controls">
    {CHtml::activeTextBox("person[passp_place]", $form)}
    {CHtml::error("person[passp_place]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[language1]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[language1]", $form, CTaxonomyManager::getLegacyTaxonomy("language")->getTermsList())}
    {CHtml::error("person[language1]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[work_place]", $form)}
    <div class="controls">
    {CHtml::activeTextBox("person[work_place]", $form)}
    {CHtml::error("person[work_place]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[dolgnost]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[dolgnost]", $form, CTaxonomyManager::getLegacyTaxonomy("dolgnost")->getTermsList())}
    {CHtml::error("person[dolgnost]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[zvanie]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[zvanie]", $form, CTaxonomyManager::getLegacyTaxonomy("zvanie")->getTermsList())}
    {CHtml::error("person[zvanie]", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("person[stepen]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("person[stepen]", $form, CTaxonomyManager::getLegacyTaxonomy("stepen")->getTermsList())}
    {CHtml::error("person[stepen]", $form)}
        </div>
</div>