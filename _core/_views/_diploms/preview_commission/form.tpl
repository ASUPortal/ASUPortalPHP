<form action="preview_comm.php" method="post" class="form-horizontal" >
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("commission[id]", $form)}


	{include file="_diploms/preview_commission/subform.common.tpl"}



    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
