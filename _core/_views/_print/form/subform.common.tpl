<div class="control-group">
    {CHtml::activeLabel("title", $form)}
    <div class="controls">
        {CHtml::activeTextField("title", $form)}
        {CHtml::error("title", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("alias", $form)}
    <div class="controls">
        {CHtml::activeTextField("alias", $form)}
        {CHtml::error("alias", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("description", $form)}
    <div class="controls">
        {CHtml::activeTextBox("description", $form)}
        {CHtml::error("description", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("formset_id", $form)}
    <div class="controls">
        {CHtml::activeDropDownList("formset_id", $form, $formsets)}
        {CHtml::error("context_evaluate", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("form_format", $form)}
    <div class="controls">
        {CHtml::activeDropDownList("form_format", $form, $types)}
        {CHtml::error("form_format", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("template_file", $form)}
    <div class="controls">
        {CHtml::activeUpload("template_file", $form)}
        {CHtml::error("template_file", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("filename_generation_strategy", $form)}
    <div class="controls">
        {CHtml::activeTextField("filename_generation_strategy", $form)}
        {CHtml::error("filename_generation_strategy", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("isActive", $form)}
    <div class="controls">
        {CHtml::activeCheckBox("isActive", $form, 1)}
        {CHtml::error("isActive", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("debug", $form)}
    <div class="controls">
        {CHtml::activeCheckBox("debug", $form, 1)}
        {CHtml::error("debug", $form)}
    </div>
</div>