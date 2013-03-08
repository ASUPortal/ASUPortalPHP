<script>
    jQuery(document).ready(function(){
        jQuery("#date_act").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<p>
    {CHtml::activeLabel("date_act", $diplom)}
    {CHtml::activeTextField("date_act", $diplom, "date_act")}
    {CHtml::error("date_act", $diplom)}
</p>

<p>
    {CHtml::activeLabel("study_mark", $diplom)}
    {CHtml::activeDropDownList("study_mark", $diplom, CTaxonomyManager::getMarksList())}
    {CHtml::error("study_mark", $diplom)}
</p>

<p>
    {CHtml::activeLabel("gak_num", $diplom)}
    {CHtml::activeTextField("gak_num", $diplom)}
    {CHtml::error("gak_num", $diplom)}
</p>

<p>
    {CHtml::activeLabel("protocol_2aspir_id", $diplom)}
    {CHtml::activeDropDownList("protocol_2aspir_id", $diplom, CProtocolManager::getAllDepProtocolsList())}
    {CHtml::error("protocol_2aspir_id", $diplom)}
</p>

<p>
    {CHtml::activeLabel("foreign_lang", $diplom)}
    {CHtml::activeDropDownList("foreign_lang", $diplom, CTaxonomyManager::getCacheLanguages()->getItems())}
    {CHtml::error("foreign_lang", $diplom)}
</p>

<p>
    {CHtml::activeLabel("recenz_id", $diplom)}
    {CHtml::activeDropDownList("recenz_id", $diplom, CStaffManager::getPersonsList())}
    {CHtml::error("recenz_id", $diplom)}
</p>