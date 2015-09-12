<h3>7.1. Основная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=1&plan_id={$plan->getId()}", $plan)}

<h3>7.2. Дополнительная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=2&plan_id={$plan->getId()}", $plan)}

<h3>7.3. Интернет-ресурсы</h3>

{CHtml::activeComponent("workplanliterature.php?type=3&plan_id={$plan->getId()}", $plan)}

<h3>7.4. Программное обеспечение</h3>

{CHtml::activeComponent("workplansoftware.php?&plan_id={$plan->getId()}", $plan)}

<h3>8. Материальное обеспечение</h3>

{CHtml::activeComponent("workplansupplies.php?&plan_id={$plan->getId()}", $plan)}

<script>
    jQuery(document).ready(function(){
        jQuery("#hardware").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>