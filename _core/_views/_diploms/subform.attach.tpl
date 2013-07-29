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

<div class="control-group">
    {CHtml::activeLabel("diplom_number", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("diplom_number", $diplom)}
        {CHtml::error("diplom_number", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("diplom_regnum", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("diplom_regnum", $diplom)}
        {CHtml::error("diplom_regnum", $diplom)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("diplom_issuedate", $diplom)}
    <div class="controls">
        {CHtml::activeTextField("diplom_issuedate", $diplom, "diplom_issuedate")}
        {CHtml::error("diplom_issuedate", $diplom)}
    </div>
</div>