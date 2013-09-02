<form action="groups.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("group[id]", $form)}

    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общая информация</a></li>
        <li><a data-toggle="tab" href="#tab-tasks">Права группы</a></li>
        <li><a data-toggle="tab" href="#tab-users">Члены группы</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_users/groups/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="tab-tasks">
            {include file="_users/groups/subform.tasks.tpl"}
        </div>
        <div class="tab-pane" id="tab-users">
            {include file="_users/groups/subform.users.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>