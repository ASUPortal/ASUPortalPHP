<div class="control-group">
    <div class="controls">
    {CHtml::activeCheckBoxGroup("user[groups]", $form, $groups)}
    {CHtml::error("user[groups]", $form)}
    </div>
</div>