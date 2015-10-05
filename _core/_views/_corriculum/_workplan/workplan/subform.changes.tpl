<div class="control-group">
    {CHtml::activeLabel("changes", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("changes", $plan)}
        {CHtml::error("changes", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("director_of_library", $plan)}
    <div class="controls">
        {CHtml::activeTextField("director_of_library", $plan)}
        {CHtml::error("director_of_library", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("chief_umr", $plan)}
    <div class="controls">
        {CHtml::activeTextField("chief_umr", $plan)}
        {CHtml::error("chief_umr", $plan)}
    </div>
</div>