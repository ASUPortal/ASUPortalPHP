<form action="form.php" method="post" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $form)}

    <p>{CHtml::errorSummary($form)}</p>

    <p>
        {CHtml::activeLabel("title", $form)}
        {CHtml::activeTextField("title", $form)}
        {CHtml::error("title", $form)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $form)}
        {CHtml::activeTextField("alias", $form)}
        {CHtml::error("alias", $form)}
    </p>

    <p>
        {CHtml::activeLabel("description", $form)}
        {CHtml::activeTextBox("description", $form)}
        {CHtml::error("description", $form)}
    </p>

    <p>
        {CHtml::activeLabel("formset_id", $form)}
        {CHtml::activeDropDownList("formset_id", $form, $formsets)}
        {CHtml::error("context_evaluate", $form)}
    </p>

    <p>
        {CHtml::activeLabel("form_format", $form)}
        {CHtml::activeDropDownList("form_format", $form, $types)}
        {CHtml::error("form_format", $form)}
    </p>

    <p>
        {CHtml::activeLabel("template_file", $form)}
        {CHtml::activeUpload("template_file", $form)}
        {CHtml::error("template_file", $form)}
    </p>

    <p>
        {CHtml::activeLabel("isActive", $form)}
        {CHtml::activeCheckBox("isActive", $form, 1)}
        {CHtml::error("isActive", $form)}
    </p>

    <p>
        {CHtml::activeLabel("debug", $form)}
        {CHtml::activeCheckBox("debug", $form, 1)}
        {CHtml::error("debug", $form)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>    
</form>