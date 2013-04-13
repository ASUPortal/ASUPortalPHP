<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
        /**
         * Нужно заблокировать роли, полученные от участия
         * в группах
         */
    });
</script>

<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("user[id]", $form)}

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общая информация</a></li>
            <li><a href="#tab-groups">Членство в группах</a></li>
            <li><a href="#tab-roles">Права пользователя</a></li>
        </ul>
        <div id="tab-common">
            {include file="_users/users/subform.common.tpl"}
        </div>
        <div id="tab-groups">
            {include file="_users/users/subform.groups.tpl"}
        </div>
        <div id="tab-roles">
            {include file="_users/users/subform.roles.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>