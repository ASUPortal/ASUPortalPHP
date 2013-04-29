<form action="index.php" method="post" enctype="multipart/form-data" id="fileupload">
    {CHtml::hiddenField("action", "fileupload")}
    {CHtml::activeHiddenField("id", $grant)}

    <p>
        {CHtml::activeLabel("upload_filename", $grant)}
        {CHtml::activeTextField("upload_filename", $grant, "", "", "required")}
    </p>

    <p>
        {CHtml::activeUpload("upload", $grant)}
    </p>

    <p>
        {CHtml::submit("Добавить файл")}
    </p>
</form>