<h3>6. Учебно-методическое и информационное обеспечение дисциплины (модуля)</h3>

<h4>6.1. Основная литература</h4>
{CHtml::activeComponent("workplanliterature.php?type=1&plan_id={$plan->getId()}", $plan)}

<h4>6.2. Дополнительная литература</h4>
{CHtml::activeComponent("workplanliterature.php?type=2&plan_id={$plan->getId()}", $plan)}

<h4>6.3. Интернет-ресурсы</h4>
{CHtml::activeComponent("workplanliterature.php?type=3&plan_id={$plan->getId()}", $plan)}

<h4>6.4. Методические указания к практическим занятиям</h4>
<div class="control-group">
    {*CHtml::activeLabel("method_practic_instructs", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("method_practic_instructs", $plan, "method_practic_instructs")}
        {CHtml::error("method_practic_instructs", $plan)}
    </div>
</div>

<h4>6.5. Методические указания к лабораторным занятиям</h4>
<div class="control-group">
    {*CHtml::activeLabel("method_labor_instructs", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("method_labor_instructs", $plan, "method_labor_instructs")}
        {CHtml::error("method_labor_instructs", $plan)}
    </div>
</div>

<h4>6.6. Методические указания к курсовому проектированию и другим видам самостоятельной работы</h4>
<div class="control-group">
    {*CHtml::activeLabel("method_project_instructs", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("method_project_instructs", $plan, "method_project_instructs")}
        {CHtml::error("method_project_instructs", $plan)}
    </div>
</div>

<h3>7. Образовательные технологии</h3>

<div class="control-group">
    {*CHtml::activeLabel("education_technologies", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("education_technologies", $plan, "education_technologies")}
        {CHtml::error("education_technologies", $plan)}
    </div>
</div>

<h4>Интерактивные образовательные технологии</h4>
{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "technologies"])}

<h4>7.1. Программное обеспечение</h4>
{CHtml::activeComponent("workplansoftware.php?&plan_id={$plan->getId()}", $plan)}

<script>
    jQuery(document).ready(function(){
        jQuery("#education_technologies").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>

<h3>8. Методические указания по освоению дисциплины</h3>
<div class="control-group">
    {*CHtml::activeLabel("method_instructs", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("method_instructs", $plan, "method_instructs")}
        {CHtml::error("method_instructs", $plan)}
    </div>
</div>

<h3>9. Материально-техническое обеспечение</h3>
<div class="control-group">
    {*CHtml::activeLabel("material_technical_supply", $plan)*}
    <div class="controls">
        {CHtml::activeTextBox("material_technical_supply", $plan, "material_technical_supply")}
        {CHtml::error("material_technical_supply", $plan)}
    </div>
</div>

{CHtml::activeComponent("workplansupplies.php?&plan_id={$plan->getId()}", $plan)}

{*
<h3>10. Адаптация рабочей программы для лиц с ОВЗ</h3>
<div class="control-group">
    {CHtml::activeLabel("adapt_for_ovz", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("adapt_for_ovz", $plan, "adapt_for_ovz")}
        {CHtml::error("adapt_for_ovz", $plan)}
    </div>
</div>
*}

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