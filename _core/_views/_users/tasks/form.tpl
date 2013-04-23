<form action="tasks.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $task)}

    <p>
        {CHtml::activeLabel("name", $task)}
        {CHtml::activeTextField("name", $task)}
        {CHtml::error("name", $task)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $task)}
        {CHtml::activeTextField("alias", $task)}
        {CHtml::error("alias", $task)}
    </p>

    <p>
        {CHtml::activeLabel("url", $task)}
        {CHtml::activeTextField("url", $task)}
        {CHtml::error("url", $task)}
    </p>

    <p>
        {CHtml::activeLabel("hidden", $task)}
        {CHtml::activeCheckBox("hidden", $task)}
        {CHtml::error("hidden", $task)}
    </p>

    <p>
        {CHtml::activeLabel("menu_name_id", $task)}
        {CHtml::activeDropDownList("menu_name_id", $task, CTaxonomyManager::getLegacyTaxonomy("task_menu_names")->getTermsList())}
        {CHtml::error("menu_name_id", $task)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $task)}
        {CHtml::activeTextField("comment", $task)}
        {CHtml::error("comment", $task)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>