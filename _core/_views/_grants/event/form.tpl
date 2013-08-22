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

<form action="events.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::activeHiddenField("id", $event)}
    {CHtml::activeHiddenField("grant_id", $event)}
    {CHtml::hiddenField("action", "save")}

    <div class="control-group">
        {CHtml::activeLabel("title", $event)}
        <div class="controls">
        {CHtml::activeTextField("title", $event)}
        {CHtml::error("title", $event)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("date_start", $event)}
        <div class="controls">
        {CHtml::activeTextField("date_start", $event, "date_start")}
        {CHtml::error("date_start", $event)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("date_end", $event)}
        <div class="controls">
        {CHtml::activeTextField("date_end", $event, "date_end")}
        {CHtml::error("date_end", $event)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("type_id", $event)}
        <div class="controls">
        {CHtml::activeDropDownList("type_id", $event, CTaxonomyManager::getTaxonomy("event_type")->getTermsList())}
        {CHtml::error("type_id", $event)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("address", $event)}
        <div class="controls">
        {CHtml::activeTextBox("address", $event)}
        {CHtml::error("address", $event)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div></div>

</form>