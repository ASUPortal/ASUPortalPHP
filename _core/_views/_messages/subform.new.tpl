<script>
    jQuery(document).ready(function(){
        jQuery("#mail_text").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "send")}
    {CHtml::activeHiddenField("from_user_id", $message)}

    <div class="control-group">
        {CHtml::activeLabel("mail_title", $message)}
        <div class="controls">
        {CHtml::activeTextField("mail_title", $message)}
        {CHtml::error("mail_title", $message)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("to_user_id", $message)}
        <div class="controls">
        {CHtml::activeDropDownList("to_user_id", $message, CStaffManager::getAllUsersList())}
        {CHtml::error("to_user_id", $message)}
    </div></div>

    <div class="control-group">
        {CHtml::activeTextBox("mail_text", $message, "mail_text")}
        {CHtml::error("mail_text", $message)}
    </div>

    <div class="control-group">
        {CHtml::activeLabel("file_name", $message)}
        <div class="controls">
        {CHtml::activeUpload("file_name", $message)}
        {CHtml::error("file_name", $message)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Отправить", false)}
    </div></div>
</form>