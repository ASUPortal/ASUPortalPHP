<script>
    jQuery(document).ready(function(){
        jQuery("#diplom_regdate").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#diplom_issuedate").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<p>
    {CHtml::activeLabel("diplom_number", $diplom)}
    {CHtml::activeTextField("diplom_number", $diplom)}
    {CHtml::error("diplom_number", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_regnum", $diplom)}
    {CHtml::activeTextField("diplom_regnum", $diplom)}
    {CHtml::error("diplom_regnum", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_regdate", $diplom)}
    {CHtml::activeTextField("diplom_regdate", $diplom, "diplom_regdate")}
    {CHtml::error("diplom_regdate", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_issuedate", $diplom)}
    {CHtml::activeTextField("diplom_issuedate", $diplom, "diplom_issuedate")}
    {CHtml::error("diplom_issuedate", $diplom)}
</p>