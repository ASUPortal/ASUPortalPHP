<form action="files.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("browserFile", $object)}
        <div class="controls">
            {CHtml::activeTextField("browserFile", $object)}
            {CHtml::error("browserFile", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("folder_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("folder_id", $object, "class.CDocumentFoldersLookup")}
            {CHtml::error("folder_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("nameFile", $object)}
        <div class="controls">
            {CHtml::activeUpload("nameFile", $object)}
            {CHtml::error("nameFile", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>