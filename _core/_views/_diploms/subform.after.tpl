<div class="control-group">
    {CHtml::activeLabel("study_mark", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("study_mark", $diplom, CTaxonomyManager::getMarksList())}
        {CHtml::error("study_mark", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("session_start", $diplom)}
    <div class="controls">
        {CHtml::activeTimeField("session_start", $diplom, "session_start")}
        {CHtml::error("session_start", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("session_end", $diplom)}
    <div class="controls">
        {CHtml::activeTimeField("session_end", $diplom, "session_end")}
        {CHtml::error("session_end", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("protocol", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("protocol", $diplom, "protocol")}
        {CHtml::error("protocol", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("order", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("order", $diplom, "order")}
        {CHtml::error("order", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("aspire_recomendation", $diplom)}
    <div class="controls">
        {CHtml::activeCheckBox("aspire_recomendation", $diplom)}
        {CHtml::error("aspire_recomendation", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("protocol_2aspir_id", $diplom)}
    <div class="controls">
        {CHtml::activeDropDownList("protocol_2aspir_id", $diplom, CProtocolManager::getAllDepProtocolsList())}
        {CHtml::error("protocol_2aspir_id", $diplom)}
    </div>
</div>