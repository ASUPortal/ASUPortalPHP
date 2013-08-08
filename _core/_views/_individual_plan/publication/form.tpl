<form action="publications.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("id_kadri", $object)}

    <div class="control-group">
        {CHtml::activeLabel("id_year", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("id_year", $object, CTaxonomyManager::getYearsList())}
            {CHtml::error("id_year", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("paper_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("paper_id", $object, $publications)}
            {CHtml::error("paper_id", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>