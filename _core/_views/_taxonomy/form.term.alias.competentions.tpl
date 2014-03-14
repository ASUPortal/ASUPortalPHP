<div class="control-group">
    {CHtml::activeLabel("alias", $term)}
    <div class="controls">
        {CHtml::activeLookup("alias", $term, "specialities")}
        {CHtml::error("alias", $term)}
    </div>
</div>