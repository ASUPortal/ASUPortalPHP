<script>
    jQuery(document).ready(function(){
        jQuery("#date_start").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#date_end").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<form action="periods.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::activeHiddenField("id", $period)}
    {CHtml::activeHiddenField("grant_id", $period)}
    {CHtml::hiddenField("action", "save")}

    <div class="control-group">
        {CHtml::activeLabel("title", $period)}
        <div class="controls">
        {CHtml::activeTextField("title", $period)}
        {CHtml::error("title", $period)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("date_start", $period)}
        <div class="controls">
        {CHtml::activeTextField("date_start", $period, "date_start")}
        {CHtml::error("date_start", $period)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("date_end", $period)}
        <div class="controls">
        {CHtml::activeTextField("date_end", $period, "date_end")}
        {CHtml::error("date_end", $period)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $period)}
        <div class="controls">
        {CHtml::activeTextBox("comment", $period)}
        {CHtml::error("comment", $period)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div></div>
</form>