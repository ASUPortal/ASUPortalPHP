<form action="preview_comm.php" method="post" class="form-horizontal" >
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("commission[id]", $form)}
	
	<ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общая информация</a></li>
        <li><a data-toggle="tab" href="#tab-students">Студенты</a></li>
        <li><a data-toggle="tab" href="#tab-members">Члены комиссии</a></li>
    </ul>
	<div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_diploms/preview_commission/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="tab-students">
            {if is_null($form->commission->getId())}
                Сохранить комиссию перед добавлением студентов
            {else}
                {include file="_diploms/preview_commission/subform.students.tpl"}
            {/if}
        </div>
        <div class="tab-pane" id="tab-members">
            {CHtml::activeComponent("members.php?comm_id={$form->commission->getId()}", $form->commission)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
