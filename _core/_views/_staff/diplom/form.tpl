<form action="diploms.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("kadri_id", $object)}

    {CHtml::errorSummary($object)}

<div class="control-group">
    {CHtml::activeLabel("obraz_type", $object)}
    <div class="controls">
        {CHtml::activeDropDownList("obraz_type", $object, $object->getTypes())}
        {CHtml::error("obraz_type", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("zaved_name", $object)}
    <div class="controls">
        {CHtml::activeTextField("zaved_name", $object)}
        {CHtml::error("zaved_name", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("god_okonch", $object)}
    <div class="controls">
        {CHtml::activeTextField("god_okonch", $object)}
        {CHtml::error("god_okonch", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("spec_name", $object)}
    <div class="controls">
        {CHtml::activeTextField("spec_name", $object)}
        {CHtml::error("spec_name", $object)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("nomer", $object)}
    <div class="controls">
        {CHtml::activeTextField("nomer", $object)}
        {CHtml::error("nomer", $object)}
    </div>
</div>

    <div class="control-group">
        {CHtml::activeLabel("seriya", $object)}
        <div class="controls">
            {CHtml::activeTextField("seriya", $object)}
            {CHtml::error("seriya", $object)}
        </div>
    </div>

<div class="control-group">
    {CHtml::activeLabel("kvalifik", $object)}
    <div class="controls">
        {CHtml::activeTextField("kvalifik", $object)}
        {CHtml::error("kvalifik", $object)}
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
        {CHtml::activeLabel("comment", $object)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $object)}
            {CHtml::error("comment", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>