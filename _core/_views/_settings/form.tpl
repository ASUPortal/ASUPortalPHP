<script>
    jQuery(document).ready(function() {
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $settings)}
{CHtml::activeHiddenField("user_id", $settings)}

    <p>{CHtml::errorSummary($settings)}</p>

    <div id="tabs">
        <ul style="height: 35px; ">
            <li><a href="#common">Общие настройки</a></li>
            <li><a href="#dashboard">Рабочий стол</a></li>
        </ul>
        <div id="common">
            <p>
                {CHtml::activeLabel("dashboard_enabled", $settings)}
                {CHtml::activeCheckBox("dashboard_enabled", $settings)}
                {CHtml::error("dashboard_enabled", $settings)}
            </p>
        </div>
        <div id="dashboard">
            <p>
                {CHtml::activeLabel("dashboard_show_birthdays", $settings)}
                {CHtml::activeCheckBox("dashboard_show_birthdays", $settings)}
                {CHtml::error("dashboard_show_birthdays", $settings)}
            </p>

            <p>
                {CHtml::activeLabel("dashboard_show_messages", $settings)}
                {CHtml::activeCheckBox("dashboard_show_messages", $settings)}
                {CHtml::error("dashboard_show_messages", $settings)}
            </p>

            <p>
                {CHtml::activeLabel("dashboard_show_all_tasks", $settings)}
                {CHtml::activeCheckBox("dashboard_show_all_tasks", $settings)}
                {CHtml::error("dashboard_show_all_tasks", $settings)}
            </p>

            <p>
                {CHtml::activeLabel("dashboard_check_messages", $settings)}
                {CHtml::activeCheckBox("dashboard_check_messages", $settings)}
                {CHtml::error("dashboard_check_messages", $settings)}
            </p>
        </div>
    </div>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>