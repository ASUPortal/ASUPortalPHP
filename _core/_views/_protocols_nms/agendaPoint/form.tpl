<form action="point.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("protocol_id", $object)}

    {CHtml::errorSummary($object)}


    <div class="control-group">
        {CHtml::activeLabel("section_id", $object)}
        <div class="controls">
            {CHtml::activeTextField("section_id", $object)}
            {CHtml::error("section_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("members", $object)}
        <div class="controls">
            {CHtml::activeLookup("members", $object, "class.CSearchCatalogStaff", true)}
            {CHtml::error("members", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text_content", $object)}
        <div class="controls">
            {CHtml::activeTextBox("text_content", $object)}
            {CHtml::error("text_content", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("opinion_id", $object)}
        <div class="controls">
            {CHtml::activeLookup("opinion_id", $object, "class.CSearchCatalogProtocolOpinion")}
            {CHtml::error("opinion_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("opinion_text", $object)}
        <div class="controls">
            {CHtml::activeTextBox("opinion_text", $object)}
            {CHtml::error("opinion_text", $object)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>