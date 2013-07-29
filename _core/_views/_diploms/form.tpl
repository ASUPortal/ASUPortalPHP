<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $diplom)}

    <p>{CHtml::errorSummary($diplom)}</p>

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#before">До защиты</a></li>
            <li><a href="#preview">Предзащита</a></li>
            <li><a href="#after">После защиты</a></li>
            <li><a href="#attach">Вкладыш</a></li>
        </ul>
        <div id="before">
            {include file="_diploms/subform.before.tpl"}
        </div>
        <div id="preview">
            {include file="_diploms/subform.preview.tpl"}
        </div>
        <div id="after">
            {include file="_diploms/subform.after.tpl"}
        </div>
        <div id="attach">
            {include file="_diploms/subform.attach.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>