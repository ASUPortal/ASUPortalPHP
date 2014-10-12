<form action="form.php" method="post" enctype="multipart/form-data" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $form)}

    {CHtml::errorSummary($form)}

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общие сведения</a></li>
        <li><a data-toggle="tab" href="#tab-properties">Параметры печати</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_print/form/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="tab-properties">
            {include file="_print/form/subform.properties.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>