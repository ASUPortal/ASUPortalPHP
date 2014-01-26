<div class="control-group">
    {CHtml::activeLabel("study_form_id", $object)}
    <div class="controls">
        {CHtml::activeLookup("study_form_id", $object, "zvanie")}
        {CHtml::error("study_form_id", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("tema", $object)}
    <div class="controls">
        {CHtml::activeTextBox("tema", $object)}
        {CHtml::error("tema", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("god_zach", $object)}
    <div class="controls">
        {CHtml::activeTextField("god_zach", $object)}
        {CHtml::error("god_zach", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("dis_sov_date", $object)}
    <div class="controls">
        {CHtml::activeDateField("dis_sov_date", $object)}
        {CHtml::error("dis_sov_date", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("dis_sov_num", $object)}
    <div class="controls">
        {CHtml::activeTextField("dis_sov_num", $object)}
        {CHtml::error("dis_sov_num", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("doc_seriya", $object)}
    <div class="controls">
        {CHtml::activeTextField("doc_seriya", $object)}
        {CHtml::error("doc_seriya", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("doc_num", $object)}
    <div class="controls">
        {CHtml::activeTextField("doc_num", $object)}
        {CHtml::error("doc_num", $object)}
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
    {CHtml::activeLabel("file_attach", $object)}
    <div class="controls">
        {CHtml::activeUpload("file_attach", $object)}
        {CHtml::error("file_attach", $object)}
    </div>
</div>