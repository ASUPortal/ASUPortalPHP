<form action="papers.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("kadri_id", $object)}
    {CHtml::activeHiddenField("disser_type", $object)}

    {CHtml::errorSummary($object)}

    {include file="_staff/paper/subform.type_{$object->type}.tpl"}

    <div class="control-group">
        <div class="controls">
                {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>