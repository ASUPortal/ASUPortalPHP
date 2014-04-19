<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $settings)}
{CHtml::activeHiddenField("user_id", $settings)}

    <p>{CHtml::errorSummary($settings)}</p>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#common" data-toggle="tab">Общие настройки</a></li>
        <li><a href="#dashboard" data-toggle="tab">Рабочий стол</a></li>
        <li><a href="#portal" data-toggle="tab">Настройки портала</a></li>
        <li><a href="#reports" data-toggle="tab">Инфографика</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="common">
            <div class="control-group">
                {CHtml::activeLabel("dashboard_enabled", $settings)}
                <div class="controls">
                {CHtml::activeCheckBox("dashboard_enabled", $settings)}
                {CHtml::error("dashboard_enabled", $settings)}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="dashboard">
            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_birthdays", $settings)}
                <div class="controls">
                {CHtml::activeCheckBox("dashboard_show_birthdays", $settings)}
                {CHtml::error("dashboard_show_birthdays", $settings)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_messages", $settings)}
                <div class="controls">
                {CHtml::activeCheckBox("dashboard_show_messages", $settings)}
                {CHtml::error("dashboard_show_messages", $settings)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_show_all_tasks", $settings)}
                <div class="controls">
                {CHtml::activeCheckBox("dashboard_show_all_tasks", $settings)}
                {CHtml::error("dashboard_show_all_tasks", $settings)}
                </div>
            </div>

            <div class="control-group">
                {CHtml::activeLabel("dashboard_check_messages", $settings)}
                <div class="controls">
                {CHtml::activeCheckBox("dashboard_check_messages", $settings)}
                {CHtml::error("dashboard_check_messages", $settings)}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="portal">
            <div class="control-group">
                {CHtml::activeLabel("portal_input_size", $settings)}
                <div class="controls">
                    {CHtml::activeDropDownList("portal_input_size", $settings, CUserSettings::getInputSizes())}
                    {CHtml::error("portal_input_size", $settings)}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="reports">
            {include file="_settings/subform.reports.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>