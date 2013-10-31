<div class="control-group">
    <div class="controls" id="roles">
    {CHtml::actionUserRolesSelector("user[roles]", $form)}
    {CHtml::error("user[roles]", $form)}
    </div>
</div>