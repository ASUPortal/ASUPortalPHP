<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $help)}

    {CHtml::errorSummary($help)}

    <div class="control-group">
        {CHtml::activeLabel("title", $help)}
        <div class="controls">
        {CHtml::activeTextField("title", $help)}
        {CHtml::error("title", $help)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("url", $help)}
        <div class="controls">
        {CHtml::activeTextField("url", $help)}
        {CHtml::error("url", $help)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeTextBox("content", $help, "content")}
        {CHtml::error("content", $help)}
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>

<script>
    jQuery(document).ready(function(){
        jQuery("#content").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'

            {if !is_null($help->id)}
            , autosave: '{$web_root}_modules/_help/index.php?action=autosave&id={$help->id}',
            interval: 30
            {/if}
        });
    });
</script>