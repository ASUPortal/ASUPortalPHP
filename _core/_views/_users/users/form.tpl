<form action="index.php" method="post" class="form-horizontal" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("user[id]", $form)}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-common" data-toggle="tab">Общая информация</a></li>
        {if !is_null($form->user->getId())}
        <li><a href="#tab-groups" data-toggle="tab">Членство в группах</a></li>
        <li><a href="#tab-roles" data-toggle="tab">Права пользователя</a></li>
        {/if}
    </ul>
    <div id="tabs" class="tab-content">
        <div id="tab-common" class="tab-pane active">
            {include file="_users/users/subform.common.tpl"}
        </div>
        {if !is_null($form->user->getId())}
        <div id="tab-groups" class="tab-pane">
            {include file="_users/users/subform.groups.tpl"}
        </div>
        <div id="tab-roles" class="tab-pane">
            {include file="_users/users/subform.roles.tpl"}
        </div>
        {/if}
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div></div>
</form>