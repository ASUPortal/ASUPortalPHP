<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $setting)}

    <p>{CHtml::errorSummary($setting)}</p>

    <p>
        {CHtml::activeLabel("title", $setting)}
        {CHtml::activeTextField("title", $setting)}
        {CHtml::error("title", $setting)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $setting)}
        {CHtml::activeTextField("alias", $setting)}
        {CHtml::error("alias", $setting)}
    </p>

    {if !isset($values)}
    <p>
        {CHtml::activeLabel("value", $setting)}
        {CHtml::activeTextBox("value", $setting)}
        {CHtml::error("value", $setting)}
    </p>
    {else}
    <p>
        {CHtml::activeLabel("value", $setting)}
        {CHtml::activeDropDownList("value", $setting, $values)}
        {CHtml::error("value", $setting)}
    </p>
    {/if}

    <p>
        {CHtml::activeLabel("type", $setting)}
        {CHtml::activeDropDownList("type", $setting, $types)}
        {CHtml::error("type", $setting)}
    </p>

    <p>
        {CHtml::activeLabel("description", $setting)}
        {CHtml::activeTextBox("description", $setting)}
        {CHtml::error("description", $setting)}
    </p>

    <p>
        {CHtml::activeLabel("params", $setting)}
        {CHtml::activeTextBox("params", $setting)}
        {CHtml::error("params", $setting)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>