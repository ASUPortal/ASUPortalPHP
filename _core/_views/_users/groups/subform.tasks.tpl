<div class="control-group" id="roles">
    <div class="controls">
    {CHtml::actionUserRolesSelector("group[roles]", $form)}
    {CHtml::error("group[roles]", $form)}
    </div>
</div>