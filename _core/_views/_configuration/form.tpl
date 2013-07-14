<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $setting)}

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

    {if !isset($values)}
    <div class="control-group">
        {CHtml::activeLabel("value", $setting)}
        <div class="controls">
            {CHtml::activeTextBox("value", $setting)}
            {CHtml::error("value", $setting)}
        </div>
    </div>
    {else}
    <div class="control-group">
        {CHtml::activeLabel("value", $setting)}
        <div class="controls">
             {CHtml::activeDropDownList("value", $setting, $values)}
             {CHtml::error("value", $setting)}
        </div>
    </div>
    {/if}

    <div class="control-group">
        {CHtml::activeLabel("type", $setting)}
        <div class="controls">
             {CHtml::activeDropDownList("type", $setting, $types)}
             {CHtml::error("type", $setting)}
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
        {CHtml::activeLabel("params", $setting)}
        <div class="controls">
            {CHtml::activeTextBox("params", $setting)}
            {CHtml::error("params", $setting)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>