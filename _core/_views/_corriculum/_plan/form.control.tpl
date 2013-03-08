<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveControl")}
    {CHtml::activeHiddenField("discipline_id", $control)}
        
    <p>{CHtml::errorSummary($control)}</p>

    <p>
        {CHtml::activeLabel("form_id", $control)}
        {CHtml::activeDropDownList("form_id", $control, CTaxonomyManager::getTaxonomy("corriculum_control_form")->getTermsList())}
        {CHtml::error("form_id", $control)}
    </p>

    <p>
        {CHtml::activeLabel("isFinal", $control)}
        {CHtml::activeCheckBox("isFinal", $control)}
        {CHtml::error("isFinal", $control)}
    </p>

    <p>
        {CHtml::activeLabel("value", $control)}
        {CHtml::activeTextField("value", $control)}
        {CHtml::error("value", $control)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>