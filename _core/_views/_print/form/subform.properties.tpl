<div class="control-group">
    {CHtml::activeLabel("properties_show_dialog", $form)}
    <div class="controls">
        {CHtml::activeCheckBox("properties_show_dialog", $form, 1)}
        {CHtml::error("properties_show_dialog", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("properties_controller", $form)}
    <div class="controls">
        {CHtml::activeTextField("properties_controller", $form)}
        {CHtml::error("properties_controller", $form)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("properties_method", $form)}
    <div class="controls">
        {CHtml::activeTextField("properties_method", $form)}
        {CHtml::error("properties_method", $form)}
    </div>
</div>