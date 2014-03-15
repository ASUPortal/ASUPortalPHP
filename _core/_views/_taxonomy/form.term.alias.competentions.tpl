<div class="control-group">
    {CHtml::activeLabel("name", $term)}
    <div class="controls">
        {CHtml::activeTextBox("name", $term)}
        {CHtml::error("name", $term)}
    </div>
</div>

<div class="control-group">
    <label for="alias" class="control-label">Специальность</label>
    <div class="controls">
        {CHtml::activeLookup("alias", $term, "specialities")}
        {CHtml::error("alias", $term)}
    </div>
</div>