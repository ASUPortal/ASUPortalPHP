<div class="control-group">
    {CHtml::activeLabel("name", $term)}
    <div class="controls">
        {CHtml::activeTextField("name", $term)}
        {CHtml::error("name", $term)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("alias", $term)}
    <div class="controls">
        {CHtml::activeTextField("alias", $term)}
        {CHtml::error("alias", $term)}
    </div>
</div>