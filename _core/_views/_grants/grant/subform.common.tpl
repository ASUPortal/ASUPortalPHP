{CHtml::activeHiddenField("grant[author_id]", $form)}

<script>
    jQuery(document).ready(function(){
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
    {CHtml::activeLabel("grant[public]", $form)}
    {CHtml::activeCheckBox("grant[public]", $form)}
    {CHtml::error("grant[public]", $form)}
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
    {CHtml::activeDateField("grant[date_start]", $form)}
    {CHtml::error("grant[date_start]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[date_end]", $form)}
    {CHtml::activeDateField("grant[date_end]", $form)}
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