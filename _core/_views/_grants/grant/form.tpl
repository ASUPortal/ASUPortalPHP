<form action="admin.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("grant[id]", $form)}
    {CHtml::hiddenField("action", "save")}

    <div data-dojo-type="dijit/layout/TabContainer" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title="Общая информация">
            {include file="_grants/grant/subform.common.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Финансирование">
            {include file="_grants/grant/subform.finances.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Мероприятия">
            {include file="_grants/grant/subform.events.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Документы">
            {include file="_grants/grant/subform.attachments.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Участники">
            {include file="_grants/grant/subform.members.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>