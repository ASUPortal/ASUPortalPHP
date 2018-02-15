<form action="point.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $protocolPoint)}
    {CHtml::activeHiddenField("protocol_id", $protocolPoint)}

    {CHtml::errorSummary($protocolPoint)}


    <div class="control-group">
        {CHtml::activeLabel("ordering", $protocolPoint)}
        <div class="controls">
            {CHtml::activeTextField("ordering", $protocolPoint)}
            {CHtml::error("ordering", $protocolPoint)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("kadri_id", $protocolPoint)}
        <div class="controls">
            {CHtml::activeLookup("kadri_id", $protocolPoint, "staff")}
            {CHtml::error("kadri_id", $protocolPoint)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text_content", $protocolPoint)}
        <div class="controls">
            {CHtml::activeTextBox("text_content", $protocolPoint)}
            {CHtml::error("text_content", $protocolPoint)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("opinion_id", $protocolPoint)}
        <div class="controls">
            {CHtml::activeLookup("opinion_id", $protocolPoint, "class.CSearchCatalogProtocolOpinion")}
            {CHtml::error("opinion_id", $protocolPoint)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("on_control", $protocolPoint)}
        <div class="controls">
            {CHtml::activeCheckBox("on_control", $protocolPoint)}
            {CHtml::error("on_control", $protocolPoint)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("opinion_text", $protocolPoint)}
        <div class="controls">
            {CHtml::activeTextBox("opinion_text", $protocolPoint)}
            {CHtml::error("opinion_text", $protocolPoint)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>