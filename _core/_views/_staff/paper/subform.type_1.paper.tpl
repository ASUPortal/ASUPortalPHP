<div class="control-group">
    {CHtml::activeLabel("tema", $object)}
    <div class="controls">
        {CHtml::activeTextBox("tema", $object)}
        {CHtml::error("tema", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("science_spec_id", $object)}
    <div class="controls">
        {CHtml::activeLookup("science_spec_id", $object, "specialities_science")}
        {CHtml::error("science_spec_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("study_form_id", $object)}
    <div class="controls">
        {CHtml::activeLookup("study_form_id", $object, "study_forms")}
        {CHtml::error("study_form_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("spec_nom", $object)}
    <div class="controls">
        {CHtml::activeTextBox("spec_nom", $object)}
        {CHtml::error("spec_nom", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $object)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $object)}
        {CHtml::error("comment", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("scinceMan", $object)}
    <div class="controls">
        {CHtml::activeLookup("scinceMan", $object, "staff")}
        {CHtml::error("scinceMan", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("file_attach", $object)}
    <div class="controls">
        {CHtml::activeUpload("file_attach", $object)}
        {CHtml::error("file_attach", $object)}
    </div>
</div>