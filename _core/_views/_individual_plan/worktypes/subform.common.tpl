<div class="control-group">
    {CHtml::activeLabel("id_razdel", $work)}
    <div class="controls">
        {CHtml::activeDropDownList("id_razdel", $work, CIndPlanWorktype::getCategories())}
        {CHtml::error("id_razdel", $work)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("name", $work)}
    <div class="controls">
        {CHtml::activeTextBox("name", $work)}
        {CHtml::error("name", $work)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("time_norm", $work)}
    <div class="controls">
        {CHtml::activeTextBox("time_norm", $work)}
        {CHtml::error("time_norm", $work)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $work)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $work)}
        {CHtml::error("comment", $work)}
    </div>
</div>