<div class="control-group">
    {CHtml::activeLabel("date_begin", $object)}
    <div class="controls">
        {CHtml::activeDateField("date_begin", $object)}
        {CHtml::error("date_begin", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("order_num_begin", $object)}
    <div class="controls">
        {CHtml::activeTextField("order_num_begin", $object)}
        {CHtml::error("order_num_begin", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_out", $object)}
    <div class="controls">
        {CHtml::activeDateField("date_out", $object)}
        {CHtml::error("date_out", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("order_num_out", $object)}
    <div class="controls">
        {CHtml::activeTextField("order_num_out", $object)}
        {CHtml::error("order_num_out", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_end", $object)}
    <div class="controls">
        {CHtml::activeDateField("date_end", $object)}
        {CHtml::error("date_end", $object)}
    </div>
</div>