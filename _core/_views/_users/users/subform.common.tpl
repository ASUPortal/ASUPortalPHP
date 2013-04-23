<p>
    {CHtml::activeLabel("user[FIO]", $form)}
    {CHtml::activeTextField("user[FIO]", $form)}
    {CHtml::error("user[FIO]", $form)}
</p>

<p>
    {CHtml::activeLabel("user[FIO_short]", $form)}
    {CHtml::activeTextField("user[FIO_short]", $form)}
    {CHtml::error("user[FIO_short]", $form)}
</p>

<p>
    {CHtml::activeLabel("user[kadri_id]", $form)}
    {CHtml::activeDropDownList("user[kadri_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("user[kadri_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("user[login]", $form)}
    {CHtml::activeTextField("user[login]", $form)}
    {CHtml::error("user[login]", $form)}
</p>

<p>
    {CHtml::activeLabel("changePassword", $form)}
    {CHtml::activeCheckBox("changePassword", $form)}
    {CHtml::error("changePassword", $form)}
</p>

<p>
    {CHtml::activeLabel("newPassword", $form)}
    {CHtml::activeTextField("newPassword", $form)}
    {CHtml::error("newPassword", $form)}
</p>

<p>
    {CHtml::activeLabel("user[comment]", $form)}
    {CHtml::activeTextBox("user[comment]", $form)}
    {CHtml::error("user[comment]", $form)}
</p>