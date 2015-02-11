<script>
    jQuery(document).ready(function(){
        jQuery("#page_content").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<form action="admin.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $page)}
    
    <div class="control-group">
        {CHtml::activeLabel("title", $page)}
        <div class="controls">
            {CHtml::activeTextField("title", $page)}
            {CHtml::error("title", $page)}
        </div>
    </div>
    {if (CSession::getCurrentUser()->getLevelForCurrentTask() == 2 or CSession::getCurrentUser()->getLevelForCurrentTask() == 4)}
    <div class="control-group">
        {CHtml::activeLabel("user_id_insert", $page)}
        <div class="controls">
        	{CHtml::activeLookup("user_id_insert", $page, "class.CSearchCatalogUsers")}
            {CHtml::error("user_id_insert", $page)}
        </div>
    </div>
    {else}
    {CHtml::activeHiddenField("user_id_insert", $page)}
	{/if}
    <p>
        {CHtml::activeTextBox("page_content", $page, "page_content")}
        {CHtml::error("page_content", $page)}
    </p>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>