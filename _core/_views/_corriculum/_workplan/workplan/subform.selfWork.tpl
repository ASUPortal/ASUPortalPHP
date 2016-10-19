<h3>4. Самостоятельная работа</h3>

<h4>4.1. Вопросы для самостоятельного изучения</h4>

{CHtml::activeComponent("workplanselfeducationblocks.php?plan_id={$plan->getId()}", $plan)}

<h4>4.2. Расчётные задания (задачи и пр.)</h4>

{CHtml::activeComponent("workplancalculationtasks.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "view"])}

<h4>4.3. Курсовой проект (работа)</h4>

<div class="control-group">
    {CHtml::activeLabel("project_description", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("project_description", $plan, "project_description")}
        {CHtml::error("project_description", $plan)}
    </div>
</div>

<strong>Темы курсовых проектов (работ)</strong>
{CHtml::activeComponent("workplanprojectthemes.php?plan_id={$plan->getId()}", $plan)}

<h4>4.4. Расчётно-графическая работа</h4>

<div class="control-group">
    {CHtml::activeLabel("rgr_description", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("rgr_description", $plan, "rgr_description")}
        {CHtml::error("rgr_description", $plan)}
    </div>
</div>

<strong>Темы расчётно-графических работ</strong>
{CHtml::activeComponent("workplanprojectthemes.php?type=1&plan_id={$plan->getId()}", $plan)}

<script>
    jQuery(document).ready(function(){
        jQuery("#project_description").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
        jQuery("#rgr_description").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>