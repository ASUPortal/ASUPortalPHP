<script>
    jQuery(document).ready(function(){
        jQuery("#page_content").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="admin.php" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $page)}
    {CHtml::activeHiddenField("user_id_insert", $page)}

    <p>
        {CHtml::activeLabel("title", $page)}
        {CHtml::activeTextField("title", $page)}
        {CHtml::error("title", $page)}
    </p>

    <p>
        {CHtml::activeTextBox("page_content", $page, "page_content")}
        {CHtml::error("page_content", $page)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>