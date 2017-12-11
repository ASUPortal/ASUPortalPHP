<div class="control-group">
    {CHtml::activeLabel("title_id", $object)}
    <div class="controls">
        {CHtml::activeDropDownList("title_id", $object, CIndPlanManager::getWorklistByCategory(3), "", "", $object->restrictionAttribute())}
        {CHtml::error("title_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_amount", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_amount", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("plan_amount", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_hours", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_hours", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("plan_hours", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_expiration_date", $object)}
    <div class="controls">
        {CHtml::activeDateField("plan_expiration_date", $object, "dd.mm.yyyy", "", "", $object->restrictionAttribute())}
        {CHtml::error("plan_expiration_date", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_report_type", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_report_type", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("plan_report_type", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $object)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $object, "", "", $object->restrictionAttribute())}
        {CHtml::error("comment", $object)}
    </div>
</div>