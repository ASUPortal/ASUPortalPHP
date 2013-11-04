<div class="control-group">
    {CHtml::activeLabel("title_id", $object)}
    <div class="controls">
        {CHtml::activeDropDownList("title_id", $object, CIndPlanManager::getWorklistByCategory(2))}
        {CHtml::error("title_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_amount", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_amount", $object)}
        {CHtml::error("plan_amount", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_expiration_date", $object)}
    <div class="controls">
        {CHtml::activeDateField("plan_expiration_date", $object)}
        {CHtml::error("plan_expiration_date", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("plan_report_type", $object)}
    <div class="controls">
        {CHtml::activeTextField("plan_report_type", $object)}
        {CHtml::error("plan_report_type", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("is_executed", $object)}
    <div class="controls">
        {CHtml::activeCheckBox("is_executed", $object)}
        {CHtml::error("is_executed", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $object)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $object)}
        {CHtml::error("comment", $object)}
    </div>
</div>