<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $diplom)}

    <p>{CHtml::errorSummary($diplom)}</p>

    <div data-dojo-type="dijit/layout/TabContainer" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title="До защиты">
            {include file="_diploms/subform.before.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="После защиты">
            {include file="_diploms/subform.after.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Вкладыш">
            {include file="_diploms/subform.attach.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>