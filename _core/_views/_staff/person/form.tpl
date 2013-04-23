<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("person[id]", $form)}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общие сведения</a></li>
        </ul>
        <div id="tab-common">
            {include file="_staff/person/subform.common.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>