<form action="index.php" method="post" class="form-horizontal" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("person[id]", $form)}

    {CHtml::errorSummary($form)}

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общие сведения</a></li>
        <li><a data-toggle="tab" href="#tab-education">Образование, диссертации</a></li>
        <li><a data-toggle="tab" href="#tab-labor">Трудовая и научная деятельность</a></li>
        <li><a data-toggle="tab" href="#tab-orders">Приказы</a></li>
        <li><a data-toggle="tab" href="#tab-info">Информация о сотруднике</a></li>
        <li><a data-toggle="tab" href="#tab-resources">Ресурсы</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_staff/person/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="tab-education">
            {include file="_staff/person/subform.education.tpl"}
        </div>
        <div class="tab-pane" id="tab-labor">
            {include file="_staff/person/subform.labor.tpl"}
        </div>
        <div class="tab-pane" id="tab-orders">
            {if ($form->person->id != "")}
                {include file="_staff/person/subform.orders.tpl"}
            {/if}
        </div>
        <div class="tab-pane" id="tab-info">
            {if ($form->person->id != "")}
                {include file="_staff/person/staffInfo/index.tpl"}
            {/if}
        </div>
        <div class="tab-pane" id="tab-resources">
            {if ($form->person->id != "")}
                {include file="_staff/person/resources/index.tpl"}
            {/if}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
