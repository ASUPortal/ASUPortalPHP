<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
        jQuery("#tabs-common").tabs();
        jQuery("#tabs-orders").tabs();
        jQuery("#tabs-orders-education").tabs();
        jQuery("#date_rogd").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
    });
</script>

<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("person[id]", $form)}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общие сведения</a></li>
            <li><a href="#tab-education">Образование, диссертации</a></li>
            <li><a href="#tab-labor">Трудовая и научная деятельность</a></li>
            <li><a href="#tab-orders">Приказы</a></li>
        </ul>
        <div id="tab-common">
            {include file="_staff/person/subform.common.tpl"}
        </div>
        <div id="tab-education">
            {include file="_staff/person/subform.education.tpl"}
        </div>
        <div id="tab-labor">
            {include file="_staff/person/subform.labor.tpl"}
        </div>
        <div id="tab-orders">
            {include file="_staff/person/subform.orders.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>