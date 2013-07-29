<form action="tasks.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $task)}

    <div class="control-group">
        {CHtml::activeLabel("name", $task)}
        <div class="controls">
        {CHtml::activeTextField("name", $task)}
        {CHtml::error("name", $task)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("alias", $task)}
        <div class="controls">
        {CHtml::activeTextField("alias", $task)}
        {CHtml::error("alias", $task)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("url", $task)}
        <div class="controls">
        {CHtml::activeTextField("url", $task)}
        {CHtml::error("url", $task)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("hidden", $task)}
        <div class="controls">
        {CHtml::activeCheckBox("hidden", $task)}
        {CHtml::error("hidden", $task)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("menu_name_id", $task)}
        <div class="controls">
        {CHtml::activeDropDownList("menu_name_id", $task, CTaxonomyManager::getLegacyTaxonomy("task_menu_names")->getTermsList())}
        {CHtml::error("menu_name_id", $task)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $task)}
        <div class="controls">
        {CHtml::activeTextField("comment", $task)}
        {CHtml::error("comment", $task)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>