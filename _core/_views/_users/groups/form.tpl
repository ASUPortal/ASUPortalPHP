<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="groups.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("group[id]", $form)}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общая информация</a></li>
            <li><a href="#tab-tasks">Права группы</a></li>
            <li><a href="#tab-users">Члены группы</a></li>
        </ul>
        <div id="tab-common">
            {include file="_users/groups/subform.common.tpl"}
        </div>
        <div id="tab-tasks">
            {include file="_users/groups/subform.tasks.tpl"}
        </div>
        <div id="tab-users">
            {include file="_users/groups/subform.users.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>