<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $diplom)}

    {CHtml::errorSummary($diplom)}

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#before">До защиты</a></li>
        <li><a data-toggle="tab" href="#preview">Предзащита</a></li>
        <li><a data-toggle="tab" href="#after">После защиты</a></li>
        <li><a data-toggle="tab" href="#attach">Вкладыш</a></li>
        <li><a data-toggle="tab" href="#info">Информация по студенту</a></li>
        <li><a data-toggle="tab" href="#antiplagiat">Антиплагиат</a></li>
    </ul>
    <div class="tab-content">
        <div id="before" class="tab-pane active">
            {include file="_diploms/subform.before.tpl"}
        </div>
        <div id="preview" class="tab-pane">
            {include file="_diploms/subform.preview.tpl"}
        </div>
        <div id="after" class="tab-pane">
            {include file="_diploms/subform.after.tpl"}
        </div>
        <div id="attach" class="tab-pane">
            {include file="_diploms/subform.attach.tpl"}
        </div>
        <div id="info" class="tab-pane">
            {include file="_diploms/subform.info.tpl"}
        </div>
        <div id="antiplagiat" class="tab-pane">
            {include file="_diploms/subform.antiplagiat.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>