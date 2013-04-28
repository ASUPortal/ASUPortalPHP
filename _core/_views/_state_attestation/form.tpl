<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("commission[id]", $form)}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общая информация</a></li>
            <li><a href="#tab-students">Студенты</a></li>
        </ul>
        <div id="tab-common">
            {include file="_state_attestation/subform.common.tpl"}
        </div>
        <div id="tab-students">
            {include file="_state_attestation/subform.students.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>