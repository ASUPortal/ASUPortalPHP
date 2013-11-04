<div class="control-group">
    {CHtml::activeLabel("change_section", $object)}
    <div class="controls">
        {CHtml::activeTextField("change_section", $object)}
        {CHtml::error("change_section", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("change_reason", $object)}
    <div class="controls">
        {CHtml::activeTextField("change_reason", $object)}
        {CHtml::error("change_reason", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("change_add_date", $object)}
    <div class="controls">
        {CHtml::activeDateField("change_add_date", $object)}
        {CHtml::error("change_add_date", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("is_executed", $object)}
    <div class="controls">
        {CHtml::activeCheckBox("is_executed", $object)}
        {CHtml::error("is_executed", $object)}
    </div>
</div>