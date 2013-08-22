<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::activeHiddenField("grant[id]", $form)}
    {CHtml::hiddenField("action", "save")}

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общая информация</a></li>
        <li><a data-toggle="tab" href="#tab-finances">Финансирование</a></li>
        <li><a data-toggle="tab" href="#tab-events">Мероприятия</a></li>
        <li><a data-toggle="tab" href="#tab-attachments">Документы</a></li>
        <li><a data-toggle="tab" href="#tab-members">Участники</a></li>
    </ul>
    <div class="tab-content">
        <div id="tab-common" class="tab-pane active">
            {include file="_grants/grant/subform.common.tpl"}
        </div>
        <div id="tab-finances" class="tab-pane">
            {include file="_grants/grant/subform.finances.tpl"}
        </div>
        <div id="tab-events" class="tab-pane">
            {include file="_grants/grant/subform.events.tpl"}
        </div>
        <div id="tab-attachments" class="tab-pane">
            {include file="_grants/grant/subform.attachments.tpl"}
        </div>
        <div id="tab-members" class="tab-pane">
            {include file="_grants/grant/subform.members.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>