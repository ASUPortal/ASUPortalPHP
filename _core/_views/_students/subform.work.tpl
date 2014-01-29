<div class="control-group">
{CHtml::activeLabel("work_current", $student)}
    <div class="controls">
                {CHtml::activeTextBox("work_current", $student)}
                {CHtml::error("work_current", $student)}
    </div> </div>

<div class="control-group">
{CHtml::activeLabel("work_proposed", $student)}
    <div class="controls">
                {CHtml::activeTextBox("work_proposed", $student)}
                {CHtml::error("work_proposed", $student)}
    </div> </div>

<div class="control-group">
{CHtml::activeLabel("comment", $student)}
    <div class="controls">
                {CHtml::activeTextBox("comment", $student)}
                {CHtml::error("comment", $student)}
    </div> </div>