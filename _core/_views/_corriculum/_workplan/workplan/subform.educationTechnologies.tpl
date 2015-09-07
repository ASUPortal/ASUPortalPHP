<h3>5. Образовательные технологии</h3>

<div class="control-group">
    {CHtml::activeLabel("education_technologies", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("education_technologies", $plan, "education_technologies")}
        {CHtml::error("education_technologies", $plan)}
    </div>
</div>

<h4>5.1. Интерактивные образовательные технологии</h4>



<script>
    jQuery(document).ready(function(){
        jQuery("#education_technologies").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>