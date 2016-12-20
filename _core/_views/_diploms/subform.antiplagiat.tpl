<div class="control-group">
    {CHtml::activeLabel("check_date_on_antiplagiat", $diplom)}
    <div class="controls">
        {CHtml::activeDateField("check_date_on_antiplagiat", $diplom)}
        {CHtml::error("check_date_on_antiplagiat", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("check_time_on_antiplagiat", $diplom)}
    <div class="controls">
        {CHtml::activeTimeField("check_time_on_antiplagiat", $diplom, "check_time_on_antiplagiat")}
        {CHtml::error("check_time_on_antiplagiat", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("borrowing_percent", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("borrowing_percent", $diplom, "borrowing")}
        {CHtml::error("borrowing_percent", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("citations_percent", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("citations_percent", $diplom, "citations")}
        {CHtml::error("citations_percent", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("originality_percent", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("originality_percent", $diplom, "originality")}
        {CHtml::error("originality_percent", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comments_on_antiplagiat", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("comments_on_antiplagiat", $diplom, "comments_on_antiplagiat")}
        {CHtml::error("comments_on_antiplagiat", $diplom)}
    </div>
</div>