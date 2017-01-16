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

<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::activeHiddenField("id", $newsItem)}
    {CHtml::activeHiddenField("user_id_insert", $newsItem)}
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("news_type", $newsItem)}

    <div class="control-group">
        {CHtml::activeLabel("title", $newsItem)}
        <div class="controls">
        {CHtml::activeTextField("title", $newsItem)}
        {CHtml::error("title", $newsItem)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::activeTextBox("file", $newsItem, "file")}
        {CHtml::error("file", $newsItem)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("date_time", $newsItem)}
        <div class="controls">
        {CHtml::activeTextField("date_time", $newsItem, "date_time")}
        {CHtml::error("date_time", $newsItem)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("image", $newsItem)}
        <div class="controls">
        {CHtml::activeUpload("image", $newsItem)}
        {CHtml::error("image", $newsItem)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("file_attach", $newsItem)}
        <div class="controls">
        {CHtml::activeUpload("file_attach", $newsItem)}
        {CHtml::error("file_attach", $newsItem)}
    </div></div>
    
    <div class="control-group">
        {CHtml::activeLabel("post_in_vk", $newsItem)}
        <div class="controls">
            {CHtml::activeCheckbox("post_in_vk", $newsItem)}
            {CHtml::error("post_in_vk", $newsItem)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div></div>
</form>