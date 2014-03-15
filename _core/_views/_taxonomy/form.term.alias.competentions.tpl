<div class="control-group">
    Специальность
    <div class="controls">
        {CHtml::activeLookup("alias", $term, "specialities")}
        {CHtml::error("alias", $term)}
    </div>
</div>