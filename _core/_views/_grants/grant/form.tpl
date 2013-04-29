<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("grant[id]", $form)}
    {CHtml::hiddenField("action", "save")}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общая информация</a></li>
            <li><a href="#tab-events">Мероприятия</a></li>
            <li><a href="#tab-attachments">Документы</a></li>
            <li><a href="#tab-members">Участники</a></li>
        </ul>
        <div id="tab-common">
            {include file="_grants/grant/subform.common.tpl"}
        </div>
        <div id="tab-events">
            {include file="_grants/grant/subform.events.tpl"}
        </div>
        <div id="tab-attachments">
            {include file="_grants/grant/subform.attachments.tpl"}
        </div>
        <div id="tab-members">
            {include file="_grants/grant/subform.members.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>