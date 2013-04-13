<form action="index.php" method="post">
    {CHtml::activeHiddenField("id", $taxonomy)}
    {CHtml::hiddenField("action", "saveLegacyTaxonomy")}

    <p>
        {CHtml::activeLabel("comment", $taxonomy)}
        {CHtml::activeTextField("comment", $taxonomy)}
        {CHtml::error("comment", $taxonomy)}
    </p>

    <p>
        {CHtml::activeLabel("sprav_name", $taxonomy)}
        {CHtml::activeTextField("sprav_name", $taxonomy)}
        {CHtml::error("sprav_name", $taxonomy)}
    </p>

    <p>
        {CHtml::activeLabel("task_id", $taxonomy)}
        {CHtml::activeDropDownList("task_id", $taxonomy, CStaffManager::getAllUserRolesList())}
        {CHtml::error("task_id", $taxonomy)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>