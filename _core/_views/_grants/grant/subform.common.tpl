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

<p>
    {CHtml::activeLabel("grant[title]", $form)}
    {CHtml::activeTextField("grant[title]", $form)}
    {CHtml::error("grant[title]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[number]", $form)}
    {CHtml::activeTextField("grant[number]", $form)}
    {CHtml::error("grant[number]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[comment]", $form)}
    {CHtml::activeTextBox("grant[comment]", $form)}
    {CHtml::error("grant[comment]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[date_start]", $form)}
    {CHtml::activeTextField("grant[date_start]", $form, "date_start")}
    {CHtml::error("grant[date_start]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[date_end]", $form)}
    {CHtml::activeTextField("grant[date_end]", $form, "date_end")}
    {CHtml::error("grant[date_end]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[manager_id]", $form)}
    {CHtml::activeDropDownList("grant[manager_id]", $form, CStaffManager::getPersonsList())}
    {CHtml::error("grant[date_end]", $form)}
</p>

<p>
    {CHtml::activeTextBox("grant[description]", $form, "description")}
    {CHtml::error("grant[description]", $form)}
</p>