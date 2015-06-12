<form action="workplantermpractices.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}

    {CHtml::errorSummary($object)}

    <div class="control-group">
        {CHtml::activeLabel("term_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("term_id", $object, $terms)}
            {CHtml::error("term_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("practice_num", $object)}
        <div class="controls">
            {CHtml::activeTextField("practice_num", $object)}
            {CHtml::error("practice_num", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("section_num", $object)}
        <div class="controls">
            {CHtml::activeTextField("section_num", $object)}
            {CHtml::error("section_num", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("title", $object)}
        <div class="controls">
            {CHtml::activeTextBox("title", $object)}
            {CHtml::error("title", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("hours", $object)}
        <div class="controls">
            {CHtml::activeTextField("hours", $object)}
            {CHtml::error("hours", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>