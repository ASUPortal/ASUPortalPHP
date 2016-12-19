<div class="control-group">
    {CHtml::activeLabel("date_check", $diplom)}
    <div class="controls">
        {CHtml::activeDateField("date_check", $diplom)}
        {CHtml::error("date_check", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("time_check", $diplom)}
    <div class="controls">
        {CHtml::activeTimeField("time_check", $diplom, "time_check")}
        {CHtml::error("time_check", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("borrowing", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("borrowing", $diplom, "borrowing")}
        {CHtml::error("borrowing", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("citations", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("citations", $diplom, "citations")}
        {CHtml::error("citations", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("originality", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("originality", $diplom, "originality")}
        {CHtml::error("originality", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comments", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("comments", $diplom, "comments")}
        {CHtml::error("comments", $diplom)}
    </div>
</div>