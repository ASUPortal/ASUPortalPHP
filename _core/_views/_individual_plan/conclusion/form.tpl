<form action="conclusions.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $conclusion)}
    {CHtml::activeHiddenField("id_kadri", $conclusion)}

    <div class="control-group">
        {CHtml::activeLabel("id_year", $conclusion)}
        <div class="controls">
            {CHtml::activeDropDownList("id_year", $conclusion, CTaxonomyManager::getYearsList())}
            {CHtml::error("id_year", $conclusion)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("msg", $conclusion)}
        <div class="controls">
            {CHtml::activeTextBox("msg", $conclusion)}
            {CHtml::error("msg", $conclusion)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>