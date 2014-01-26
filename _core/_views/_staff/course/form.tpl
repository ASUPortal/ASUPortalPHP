<form action="courses.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("kadri_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("name", $object)}
    <div class="controls">
        {CHtml::activeTextBox("name", $object)}
        {CHtml::error("name", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("place", $object)}
    <div class="controls">
        {CHtml::activeTextBox("place", $object)}
        {CHtml::error("place", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_start", $object)}
    <div class="controls">
        {CHtml::activeDateField("date_start", $object)}
        {CHtml::error("date_start", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_end", $object)}
    <div class="controls">
        {CHtml::activeDateField("date_end", $object)}
        {CHtml::error("date_end", $object)}
    </div>
</div>

    <div class="control-group">
        {CHtml::activeLabel("document", $object)}
        <div class="controls">
            {CHtml::activeTextBox("document", $object)}
            {CHtml::error("document", $object)}
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

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>