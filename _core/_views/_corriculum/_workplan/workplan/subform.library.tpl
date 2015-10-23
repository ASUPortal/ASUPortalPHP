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

<h3>9. Материальное обеспечение</h3>

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
        jQuery("#hardware").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>