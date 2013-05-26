<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("commission[id]", $form)}

    <div data-dojo-type="dijit/layout/TabContainer" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title="Общая информация">
            {include file="_state_attestation/subform.common.tpl"}
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Студенты">
            {if is_null($form->commission->getId())}
                Сохранить комиссию перед добавлением студентов
            {else}
                {include file="_state_attestation/subform.students.tpl"}
            {/if}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>