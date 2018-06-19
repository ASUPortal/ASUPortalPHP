<form action="preview.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $preview)}
    {CHtml::activeHiddenField("diplom_id", $preview)}
    {CHtml::activeHiddenField("student_id", $preview)}

    {CHtml::errorSummary($preview)}

<div class="control-group">
    {CHtml::activeLabel("diplom_percent", $preview)}
    <div class="controls">
        {CHtml::activeTextField("diplom_percent", $preview)}
        {CHtml::error("diplom_percent", $preview)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("another_view", $preview)}
    <div class="controls">
        {CHtml::activeCheckBox("another_view", $preview)}
        {CHtml::error("another_view", $preview)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("date_preview", $preview)}
    <div class="controls">
        {CHtml::activeDateField("date_preview", $preview)}
        {CHtml::error("date_preview", $preview)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comm_id", $preview)}
    <div class="controls">
        {CHtml::activeLookup("comm_id", $preview, "class.CSearchCatalogPreviewCommission", false, array(), true)}
        {CHtml::error("comm_id", $preview)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("comment", $preview)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $preview)}
        {CHtml::error("comment", $preview)}
    </div>
</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>