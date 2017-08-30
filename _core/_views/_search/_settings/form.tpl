<form action="settings.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $setting)}
{CHtml::activeHiddenField("solr", $setting)}

    {CHtml::errorSummary($setting)}

    <div class="control-group">
        {CHtml::activeLabel("title", $setting)}
        <div class="controls">
            {CHtml::activeTextField("title", $setting)}
            {CHtml::error("title", $setting)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("alias", $setting)}
        <div class="controls">
            {CHtml::activeTextField("alias", $setting)}
            {CHtml::error("alias", $setting)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("value", $setting)}
        <div class="controls">
            {CHtml::activeTextField("value", $setting)}
            {CHtml::error("value", $setting)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("description", $setting)}
        <div class="controls">
            {CHtml::activeTextBox("description", $setting)}
            {CHtml::error("description", $setting)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>