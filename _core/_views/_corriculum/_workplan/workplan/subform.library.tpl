<h3>7.1. Основная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=1&plan_id={$plan->getId()}", $plan)}

<h3>7.2. Дополнительная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=2&plan_id={$plan->getId()}", $plan)}

<h3>7.3. Интернет-ресурсы</h3>

{CHtml::activeComponent("workplanliterature.php?type=3&plan_id={$plan->getId()}", $plan)}

<h3>7.4. Программное обеспечение</h3>

{CHtml::activeComponent("workplansoftware.php?&plan_id={$plan->getId()}", $plan)}

<h3>8. Методические указания по освоению дисциплины</h3>
<div class="control-group">
    {CHtml::activeLabel("method_instructs", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("method_instructs", $plan, "method_instructs")}
        {CHtml::error("method_instructs", $plan)}
    </div>
</div>

<h4>8.1 Методические указания к практическим занятиям</h4>
<div class="control-group">
    {CHtml::activeLabel("method_practic_instructs", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("method_practic_instructs", $plan, "method_practic_instructs")}
        {CHtml::error("method_practic_instructs", $plan)}
    </div>
</div>

<h4>8.2 Методические указания к лабораторным занятиям</h4>
<div class="control-group">
    {CHtml::activeLabel("method_labor_instructs", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("method_labor_instructs", $plan, "method_labor_instructs")}
        {CHtml::error("method_labor_instructs", $plan)}
    </div>
</div>

<h4>8.3 Методические указания к курсовому проектированию и другим видам самостоятельной работы</h4>
<div class="control-group">
    {CHtml::activeLabel("method_project_instructs", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("method_project_instructs", $plan, "method_project_instructs")}
        {CHtml::error("method_project_instructs", $plan)}
    </div>
</div>

<h3>9. Материально-техническое обеспечение</h3>
<div class="control-group">
    {CHtml::activeLabel("material_technical_supply", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("material_technical_supply", $plan, "material_technical_supply")}
        {CHtml::error("material_technical_supply", $plan)}
    </div>
</div>

{CHtml::activeComponent("workplansupplies.php?&plan_id={$plan->getId()}", $plan)}

<h3>10. Адаптация рабочей программы для лиц с ОВЗ</h3>
<div class="control-group">
    {CHtml::activeLabel("adapt_for_ovz", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("adapt_for_ovz", $plan, "adapt_for_ovz")}
        {CHtml::error("adapt_for_ovz", $plan)}
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("#method_instructs").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
        jQuery("#method_practic_instructs").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
        jQuery("#method_labor_instructs").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
        jQuery("#method_project_instructs").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>