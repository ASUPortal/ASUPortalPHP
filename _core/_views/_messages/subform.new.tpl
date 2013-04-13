<script>
    jQuery(document).ready(function(){
        jQuery("#mail_text").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="index.php" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "send")}
    {CHtml::activeHiddenField("from_user_id", $message)}

    <p>
        {CHtml::activeLabel("mail_title", $message)}
        {CHtml::activeTextField("mail_title", $message)}
        {CHtml::error("mail_title", $message)}
    </p>

    <p>
        {CHtml::activeLabel("to_user_id", $message)}
        {CHtml::activeDropDownList("to_user_id", $message, CStaffManager::getAllUsersList())}
        {CHtml::error("to_user_id", $message)}
    </p>

    <p>
        {CHtml::activeTextBox("mail_text", $message, "mail_text")}
        {CHtml::error("mail_text", $message)}
    </p>

    <p>
        {CHtml::activeLabel("file_name", $message)}
        {CHtml::activeUpload("file_name", $message)}
        {CHtml::error("file_name", $message)}
    </p>

    <p>
        {CHtml::submit("Отправить")}
    </p>
</form>