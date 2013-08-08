<form action="changes.php" method="post" enctype="multipart/form-data" class="form-horizontal">
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
        {CHtml::activeLabel("razdel", $object)}
        <div class="controls">
            {CHtml::activeTextField("razdel", $object)}
            {CHtml::error("razdel", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("izmenenie", $object)}
        <div class="controls">
            {CHtml::activeTextBox("izmenenie", $object)}
            {CHtml::error("izmenenie", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("zav", $object)}
        <div class="controls">
            {CHtml::activeDateField("zav", $object)}
            {CHtml::error("zav", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("prep", $object)}
        <div class="controls">
            {CHtml::activeDateField("prep", $object)}
            {CHtml::error("prep", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("id_otmetka", $object)}
        <div class="controls">
            {CHtml::activeCheckBox("id_otmetka", $object, "1")}
            {CHtml::error("id_otmetka", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>