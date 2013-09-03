<form action="worktypes.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $work)}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#common" data-toggle="tab">Общая информация</a></li>
        <li><a href="#completion" data-toggle="tab">Автозаполнение</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="common">
            {include file="_individual_plan/worktypes/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="completion">
            {include file="_individual_plan/worktypes/subform.completion.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>