<div class="control-group">
    {CHtml::activeLabel("change_section", $object)}
    <div class="controls">
        {CHtml::activeTextField("change_section", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("change_section", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("change_reason", $object)}
    <div class="controls">
        {CHtml::activeTextField("change_reason", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("change_reason", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("change_add_date", $object)}
    <div class="controls">
        {CHtml::activeDateField("change_add_date", $object, "dd.mm.yyyy", "", "", $object->restrictionAttribute())}
        {CHtml::error("change_add_date", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("is_executed", $object)}
    <div class="controls">
        {CHtml::activeCheckBox("is_executed", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("is_executed", $object)}
    </div>
</div>