{CHtml::activeHiddenField("grant[author_id]", $form)}

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
        jQuery(document).ready(function(){
            jQuery("#description").redactor({
                imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
            });
        });
    });
</script>

<div class="control-group">
    {CHtml::activeLabel("grant[title]", $form)}
    <div class="controls">
    {CHtml::activeTextField("grant[title]", $form)}
    {CHtml::error("grant[title]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("grant[number]", $form)}
    <div class="controls">
    {CHtml::activeTextField("grant[number]", $form)}
    {CHtml::error("grant[number]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("grant[comment]", $form)}
    <div class="controls">
    {CHtml::activeTextBox("grant[comment]", $form)}
    {CHtml::error("grant[comment]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("grant[date_start]", $form)}
    <div class="controls">
    {CHtml::activeTextField("grant[date_start]", $form, "date_start")}
    {CHtml::error("grant[date_start]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("grant[date_end]", $form)}
    <div class="controls">
    {CHtml::activeTextField("grant[date_end]", $form, "date_end")}
    {CHtml::error("grant[date_end]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("grant[manager_id]", $form)}
    <div class="controls">
    {CHtml::activeDropDownList("grant[manager_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("grant[date_end]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeTextBox("grant[description]", $form, "description")}
    {CHtml::error("grant[description]", $form)}
</div>