<p>
    {CHtml::activeLabel("study_mark", $diplom)}
    {CHtml::activeDropDownList("study_mark", $diplom, CTaxonomyManager::getMarksList())}
    {CHtml::error("study_mark", $diplom)}
</p>

<p>
    {CHtml::activeLabel("session_start", $diplom)}
    {CHtml::activeTimeField("session_start", $diplom, "session_start")}
    {CHtml::error("session_start", $diplom)}
</p>

<p>
    {CHtml::activeLabel("session_end", $diplom)}
    {CHtml::activeTimeField("session_end", $diplom, "session_end")}
    {CHtml::error("session_end", $diplom)}
</p>

<p>
    {CHtml::activeLabel("aspire_recomendation", $diplom)}
    {CHtml::activeCheckBox("aspire_recomendation", $diplom)}
    {CHtml::error("aspire_recomendation", $diplom)}
</p>

<br>

<p>
    {CHtml::activeLabel("protocol_2aspir_id", $diplom)}
    {CHtml::activeDropDownList("protocol_2aspir_id", $diplom, CProtocolManager::getAllDepProtocolsList())}
    {CHtml::error("protocol_2aspir_id", $diplom)}
</p>