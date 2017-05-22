<div class="control-group">
    {CHtml::activeLabel("user[FIO]", $form)}
    <div class="controls">
    {CHtml::activeTextField("user[FIO]", $form)}
    {CHtml::error("user[FIO]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("user[FIO_short]", $form)}
    <div class="controls">
    {CHtml::activeTextField("user[FIO_short]", $form)}
    {CHtml::error("user[FIO_short]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("user[kadri_id]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("user[kadri_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("user[kadri_id]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("user[photo]", $form)}
    <div class="controls">
    {CHtml::activeUpload("user[photo]", $form)}
    {CHtml::error("user[photo]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("user[login]", $form)}
    <div class="controls">
    {CHtml::activeTextField("user[login]", $form)}
    {CHtml::error("user[login]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("changePassword", $form)}
    <div class="controls">
    {CHtml::activeCheckBox("changePassword", $form)}
    {CHtml::error("changePassword", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("newPassword", $form)}
    <div class="controls">
    {CHtml::activeTextField("newPassword", $form)}
    {CHtml::error("newPassword", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("user[comment]", $form)}
    <div class="controls">
    {CHtml::activeTextBox("user[comment]", $form)}
    {CHtml::error("user[comment]", $form)}
</div></div>