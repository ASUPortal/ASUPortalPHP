<script>
    jQuery(document).ready(function(){
        jQuery("#date_time").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#file").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="index.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $newsItem)}
    {CHtml::activeHiddenField("user_id_insert", $newsItem)}
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("news_type", $newsItem)}

    <p>
        {CHtml::activeLabel("title", $newsItem)}
        {CHtml::activeTextField("title", $newsItem)}
        {CHtml::error("title", $newsItem)}
    </p>

    <p>
        {CHtml::activeTextBox("file", $newsItem, "file")}
        {CHtml::error("file", $newsItem)}
    </p>

    <p>
        {CHtml::activeLabel("date_time", $newsItem)}
        {CHtml::activeTextField("date_time", $newsItem, "date_time")}
        {CHtml::error("date_time", $newsItem)}
    </p>

    <p>
        {CHtml::activeLabel("image", $newsItem)}
        {CHtml::activeUpload("image", $newsItem)}
        {CHtml::error("image", $newsItem)}
    </p>

    <p>
        {CHtml::activeLabel("file_attach", $newsItem)}
        {CHtml::activeUpload("file_attach", $newsItem)}
        {CHtml::error("file_attach", $newsItem)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>