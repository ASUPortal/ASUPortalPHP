<form action="formset.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $set)}

    <p>{CHtml::errorSummary($set)}</p>

    <p>
        {CHtml::activeLabel("title", $set)}
        {CHtml::activeTextField("title", $set)}
        {CHtml::error("title", $set)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $set)}
        {CHtml::activeTextField("alias", $set)}
        {CHtml::error("alias", $set)}
    </p>

    <p>
        {CHtml::activeLabel("description", $set)}
        {CHtml::activeTextBox("description", $set)}
        {CHtml::error("description", $set)}
    </p>

    <p>
        {CHtml::activeLabel("context_evaluate", $set)}
        {CHtml::activeTextBox("context_evaluate", $set)}
        {CHtml::error("context_evaluate", $set)}
    </p>

    <p>
        {CHtml::activeLabel("context_variables", $set)}
        {CHtml::activeTextBox("context_variables", $set)}
        {CHtml::error("context_variables", $set)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>    
</form>