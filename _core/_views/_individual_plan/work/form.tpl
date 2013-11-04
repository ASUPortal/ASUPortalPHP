<form action="work.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("load_id", $object)}
    {CHtml::activeHiddenField("work_type", $object)}

    {CHtml::errorSummary($object)}

    {include file="_individual_plan/work/form_{$object->work_type}.tpl"}

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>