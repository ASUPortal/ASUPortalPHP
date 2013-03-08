<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $help)}

    <p>{CHtml::errorSummary($help)}</p>

    <p>
        {CHtml::activeLabel("title", $help)}
        {CHtml::activeTextField("title", $help)}
        {CHtml::error("title", $help)}
    </p>

    <p>
        {CHtml::activeLabel("url", $help)}
        {CHtml::activeTextField("url", $help)}
        {CHtml::error("url", $help)}
    </p>

    <div>
        {CHtml::activeTextBox("content", $help, "content")}
        {CHtml::error("content", $help)}
    </div>

    {include file="_core.acl.tpl" table=$help}

    <p>
        {CHtml::submit("Сохранить")}
    </p>
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