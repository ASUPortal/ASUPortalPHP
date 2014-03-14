<div class="control-group">
    {CHtml::activeLabel("alias", $term)}
    <div class="controls">
        {CHtml::activeTextField("alias", $term)}
        {CHtml::error("alias", $term)}
    </div>
</div>