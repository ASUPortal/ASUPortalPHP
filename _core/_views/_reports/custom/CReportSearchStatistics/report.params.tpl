<form class="form-horizontal">
    <div class="control-group">
        {CHtml::activeLabel("start", $report)}
        <div class="controls">
            {CHtml::activeDateField("start", $report)}
            {CHtml::error("start", $report)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("end", $report)}
        <div class="controls">
            {CHtml::activeDateField("end", $report)}
            {CHtml::error("end", $report)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Построить", false)}
        </div></div>
</form>