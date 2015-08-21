{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Изменение личных настроек группы: {CStaffManager::getUserGroup(CRequest::getInt("id"))->comment}</h2>

    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "changeUsersSettingsProcess")}
        {CHtml::activeHiddenField("users", $form)}

        {CHtml::errorSummary($form)}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#common" data-toggle="tab">Общие настройки</a></li>
        <li><a href="#dashboard" data-toggle="tab">Рабочий стол</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="common">
            <div class="control-group">
                {CHtml::activeLabel("dashboard_enabled_groups", $form)}
                <div class="controls">
                {CHtml::activeDropDownList("dashboard_enabled_groups", $form, $options)}
                {CHtml::error("dashboard_enabled_groups", $form)}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="dashboard">
            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_birthdays_groups", $form)}
                <div class="controls">
                {CHtml::activeDropDownList("dashboard_show_birthdays_groups", $form, $options)}
                {CHtml::error("dashboard_show_birthdays_groups", $form)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_messages_groups", $form)}
                <div class="controls">
                {CHtml::activeDropDownList("dashboard_show_messages_groups", $form, $options)}
                {CHtml::error("dashboard_show_messages_groups", $form)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_all_tasks_groups", $form)}
                <div class="controls">
                {CHtml::activeDropDownList("dashboard_show_all_tasks_groups", $form, $options)}
                {CHtml::error("dashboard_show_all_tasks_groups", $form)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_check_messages_groups", $form)}
                <div class="controls">
                {CHtml::activeDropDownList("dashboard_check_messages_groups", $form, $options)}
                {CHtml::error("dashboard_check_messages_groups", $form)}
                </div>
            </div>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>
{/block}

{block name="asu_right"}
    {include file="_settings/index.right.tpl"}
{/block}