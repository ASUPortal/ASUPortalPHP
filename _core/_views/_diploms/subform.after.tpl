<script>
    jQuery(document).ready(function(){
        jQuery("#session_end").timepicker({
            hourText: "Часы",
            minuteText: "Минуты",
            showNowButton: true,
            nowButtonText: "Сейчас",
            showPeriodLabels: false
        });
        jQuery("#session_start").timepicker({
            hourText: "Часы",
            minuteText: "Минуты",
            showNowButton: true,
            nowButtonText: "Сейчас",
            showPeriodLabels: false
        });
    });
</script>

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
        {CHtml::activeTextField("session_start", $diplom, "session_start")}
        {CHtml::error("session_start", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("session_end", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("session_end", $diplom, "session_end")}
        {CHtml::error("session_end", $diplom)}
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